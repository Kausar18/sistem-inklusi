<?php

namespace App\Support;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * Kumpulan parser untuk membersihkan data mentah dari Excel.
 *
 * Data asli ditulis bebas oleh responden, sehingga satu kolom bisa
 * berisi banyak variasi format. Semua logika pembersihan dikumpulkan
 * di sini supaya mudah diperbaiki jika ditemukan format baru.
 */
class ParserData
{
    /**
     * Ubah teks nilai uang menjadi angka.
     *
     * Contoh yang ditangani:
     *   "150.000.000"                        -> 150000000
     *   "Rp1.400.000.000"                    -> 1400000000
     *   "Rp.493.008.143,-"                   -> 493008143
     *   "Rp. 58.000.000 (Agustus - Desember)"-> 58000000
     *   "3,2 Milliar"                        -> 3200000000
     *   "661 Juta"                           -> 661000000
     *   "RP. 150 JUTA"                       -> 150000000
     */
    public static function uang($nilai): ?float
    {
        if ($nilai === null || $nilai === '') {
            return null;
        }

        // Sudah berupa angka murni dari Excel
        if (is_int($nilai) || is_float($nilai)) {
            return (float) $nilai;
        }

        $teks = strtolower(trim((string) $nilai));

        // Buang keterangan dalam kurung, mis. "(Agustus - Desember)"
        $teks = preg_replace('/\([^)]*\)/', ' ', $teks);

        // Buang simbol mata uang dan akhiran ",-"
        $teks = str_replace(['rp.', 'rp', ',-'], ' ', $teks);

        if (trim($teks) === '') {
            return null;
        }

        // ------------------------------------------------------------------
        // Tahap 1: cari angka yang MENEMPEL pada satuan (juta / miliar / M).
        // Ini menangani "3,2 Milliar", "Rp 1,7 M", "661 Juta", dan juga
        // "Per Tanggal 1 Januari 2026 ... Rp 255 Juta" (angka tanggal diabaikan).
        // ------------------------------------------------------------------
        $terbesar = null;

        if (preg_match_all('/(\d[\d.,]*)\s*(mil+[iy]ar|juta|ribu|m)(?![a-z])/u', $teks, $cocok, PREG_SET_ORDER)) {
            foreach ($cocok as $bagian) {
                $angka = $bagian[1];
                $satuan = $bagian[2];

                $pengali = match (true) {
                    str_starts_with($satuan, 'mil'), $satuan === 'm' => 1_000_000_000,
                    $satuan === 'juta'                               => 1_000_000,
                    default                                          => 1_000,
                };

                // "3,2" / "1.6" -> desimal; selain itu titik/koma = pemisah ribuan
                if (preg_match('/^\d+[.,]\d{1,2}$/', $angka)) {
                    $nilaiAngka = (float) str_replace(',', '.', $angka);
                } else {
                    $nilaiAngka = (float) str_replace(['.', ','], '', $angka);
                }

                $hasil = $nilaiAngka * $pengali;

                if ($terbesar === null || $hasil > $terbesar) {
                    $terbesar = $hasil;
                }
            }

            return $terbesar;
        }

        // ------------------------------------------------------------------
        // Tahap 2: tanpa satuan -> ambil angka TERBESAR dalam teks.
        // Ini mencegah tahun ("2026") terbaca sebagai nilai omset pada teks
        // seperti "Omset Januari - April 2026 Rp. 1.352.934.591".
        // ------------------------------------------------------------------
        if (preg_match_all('/\d[\d.,]*/', $teks, $cocok)) {
            foreach ($cocok[0] as $token) {
                $bersih = str_replace(['.', ','], '', $token);

                if ($bersih === '' || ! ctype_digit($bersih)) {
                    continue;
                }

                $hasil = (float) $bersih;

                if ($terbesar === null || $hasil > $terbesar) {
                    $terbesar = $hasil;
                }
            }
        }

        return $terbesar;
    }

    /** Ubah "Laki-Laki"/"Perempuan"/"Pria"/"Wanita" menjadi L atau P. */
    public static function gender($nilai): ?string
    {
        if (blank($nilai)) {
            return null;
        }

        $teks = strtolower(trim((string) $nilai));

        if (str_starts_with($teks, 'p') && ! str_starts_with($teks, 'pria')) {
            return 'P'; // perempuan
        }

        if (str_contains($teks, 'wanita')) {
            return 'P';
        }

        return 'L';
    }

    /**
     * Tentukan asal invensi produk.
     *
     * "Sendiri"                      -> Mandiri
     * "IPB"                          -> IPB
     * "Sendiri + Dosen IPB"          -> Kombinasi
     * "Kerjasama antara ..., IPB, .."-> Kombinasi
     */
    public static function asalInvensi($nilai): string
    {
        if (blank($nilai)) {
            return 'Mandiri';
        }

        $teks = strtolower((string) $nilai);
        $adaIpb = str_contains($teks, 'ipb');
        $adaSendiri = str_contains($teks, 'sendiri')
            || str_contains($teks, 'kerjasama')
            || str_contains($teks, 'kombinasi');

        if ($adaIpb && $adaSendiri) {
            return 'Kombinasi';
        }

        if ($adaIpb) {
            return 'IPB';
        }

        return 'Mandiri';
    }

    /**
     * Pecah daftar legalitas menjadi array.
     *
     * "PT dan NIB"        -> ['PT', 'NIB']
     * "Halal, PIRT, HAKI" -> ['Halal', 'PIRT', 'HAKI']
     * "Belum mimiliki"    -> []  (termasuk typo di data asli)
     * "Masih dalam proses"-> []
     */
    public static function legalitas($nilai): array
    {
        if (blank($nilai)) {
            return [];
        }

        $teks = trim((string) $nilai);
        $cek = strtolower($teks);

        // Status "belum punya" tidak dicatat sebagai legalitas
        $polaKosong = ['belum mimiliki', 'belum memiliki', 'belum ada', 'tidak ada', '-'];
        foreach ($polaKosong as $pola) {
            if ($cek === $pola || str_starts_with($cek, $pola)) {
                return [];
            }
        }

        // Sedang diproses -> simpan apa adanya sebagai catatan status
        if (str_contains($cek, 'dalam proses') || str_contains($cek, 'masih proses')) {
            return [$teks];
        }

        // Pisah berdasarkan koma, "dan", atau "&"
        $bagian = preg_split('/\s*,\s*|\s+dan\s+|\s*&\s*/i', $teks);

        return collect($bagian)
            ->map(fn ($b) => trim($b))
            ->filter(fn ($b) => $b !== '' && strlen($b) <= 150)
            ->values()
            ->all();
    }

    /**
     * Pecah kolom tim inti manajemen menjadi daftar anggota.
     *
     * Format asli (satu sel):
     *   "1. 3 Orang
     *    2. Adi Jaeludin, Produksi (Pria), Ansellma Putri, Keuangan (Wanita)"
     *
     * Hasil: [['nama'=>'Adi Jaeludin','jabatan'=>'Produksi','jenis_kelamin'=>'L'], ...]
     */
    public static function anggotaTim($nilai): array
    {
        if (blank($nilai)) {
            return [];
        }

        $teks = (string) $nilai;

        // Buang penomoran "1." / "2." di awal baris
        $teks = preg_replace('/^\s*\d\.\s*/m', '', $teks);

        // Pola: Nama, Jabatan (Gender)
        preg_match_all('/([^,()\n]+?)\s*,\s*([^,()\n]+?)\s*\(([^)]+)\)/u', $teks, $cocok, PREG_SET_ORDER);

        $hasil = [];

        foreach ($cocok as $baris) {
            $nama = trim($baris[1]);
            $jabatan = trim($baris[2]);
            $gender = self::gender($baris[3]);

            // Lewati jika hasil tangkapan bukan nama orang
            if ($nama === '' || is_numeric($nama) || mb_strlen($nama) > 150) {
                continue;
            }

            $hasil[] = [
                'nama'          => $nama,
                'jabatan'       => mb_substr($jabatan, 0, 100),
                'jenis_kelamin' => $gender,
            ];
        }

        return $hasil;
    }

    /**
     * Ekstrak nama kota/kabupaten dari teks alamat bebas.
     *
     * CATATAN: hasil ekstraksi ini perlu diverifikasi manual setelah import,
     * karena penulisan alamat di data asli sangat bervariasi.
     */
    public static function kota($alamat): ?string
    {
        if (blank($alamat)) {
            return null;
        }

        $teks = (string) $alamat;

        // Pola eksplisit: "Kabupaten Bogor", "Kab. Cianjur", "Kota Bogor"
        if (preg_match('/\b(kabupaten|kab\.?|kota)\s+([A-Za-z\s]{3,30}?)(?=[,.\n]|\s+provinsi|\s+jawa|$)/iu', $teks, $cocok)) {
            $jenis = str_starts_with(strtolower($cocok[1]), 'kota') ? 'Kota' : 'Kabupaten';

            return $jenis . ' ' . ucwords(strtolower(trim($cocok[2])));
        }

        // Cocokkan dengan daftar kota yang sering muncul di data
        foreach (self::DAFTAR_KOTA as $kota) {
            if (stripos($teks, $kota) !== false) {
                return $kota;
            }
        }

        return null;
    }

    /** Daftar kota/kabupaten yang muncul di data tenant. */
    private const DAFTAR_KOTA = [
        'Bogor', 'Depok', 'Bekasi', 'Tangerang', 'Jakarta', 'Bandung',
        'Cianjur', 'Sukabumi', 'Boyolali', 'Wonosobo', 'Sarolangun',
        'Semarang', 'Yogyakarta', 'Surabaya', 'Malang', 'Solo', 'Bali',
        'Medan', 'Makassar', 'Lampung', 'Cirebon', 'Sukoharjo', 'Karawang',
        'Serang', 'Cilegon', 'Purwokerto', 'Klaten', 'Sleman', 'Bantul',
    ];

    /** Ambil provinsi dari teks alamat jika disebutkan. */
    public static function provinsi($alamat): ?string
    {
        if (blank($alamat)) {
            return null;
        }

        $daftar = [
            'Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'DKI Jakarta',
            'Banten', 'DI Yogyakarta', 'Yogyakarta', 'Bali', 'Jambi',
            'Sumatera Utara', 'Sumatera Barat', 'Sumatera Selatan', 'Lampung',
        ];

        foreach ($daftar as $provinsi) {
            if (stripos((string) $alamat, $provinsi) !== false) {
                return $provinsi;
            }
        }

        return null;
    }

    /** Ubah berbagai format tanggal menjadi Carbon, atau null jika gagal. */
    public static function tanggal($nilai): ?Carbon
    {
        if (blank($nilai)) {
            return null;
        }

        // Angka serial Excel
        if (is_numeric($nilai) && $nilai > 1000) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $nilai));
            } catch (\Throwable) {
                return null;
            }
        }

        $teks = trim((string) $nilai);

        $format = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'm/d/Y', 'Y/m/d', 'd F Y', 'Y-m-d H:i:s'];

        foreach ($format as $f) {
            try {
                $tanggal = Carbon::createFromFormat($f, $teks);
                if ($tanggal !== false) {
                    return $tanggal->startOfDay();
                }
            } catch (\Throwable) {
                continue;
            }
        }

        try {
            return Carbon::parse($teks)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    /** Ubah nilai jumlah tenaga kerja menjadi integer. */
    public static function bilangan($nilai): int
    {
        if (blank($nilai)) {
            return 0;
        }

        if (is_int($nilai) || is_float($nilai)) {
            return (int) $nilai;
        }

        if (preg_match('/\d+/', (string) $nilai, $cocok)) {
            return (int) $cocok[0];
        }

        return 0;
    }

    /** Rapikan teks: buang spasi berlebih, kembalikan null jika kosong. */
    public static function teks($nilai, ?int $maks = null): ?string
    {
        if (blank($nilai)) {
            return null;
        }

        $teks = trim(preg_replace('/\s+/u', ' ', (string) $nilai));

        if ($teks === '' || $teks === '-') {
            return null;
        }

        return $maks ? mb_substr($teks, 0, $maks) : $teks;
    }

    /** Pisahkan website dan instagram dari satu kolom bebas. */
    public static function instagram($nilai): ?string
    {
        $teks = self::teks($nilai, 150);

        if ($teks === null) {
            return null;
        }

        return str_contains(strtolower($teks), 'instagram') || str_starts_with($teks, '@')
            ? $teks
            : null;
    }
}
