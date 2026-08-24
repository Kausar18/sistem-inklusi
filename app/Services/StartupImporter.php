<?php

namespace App\Services;

use App\Models\AnggotaTim;
use App\Models\BidangUsaha;
use App\Models\Dokumentasi;
use App\Models\ImportLog;
use App\Models\Legalitas;
use App\Models\Startup;
use App\Support\ParserData;
use Illuminate\Support\Facades\DB;

/**
 * Memetakan baris Excel "Rekap Database Tenant" ke tabel database.
 *
 * Header kolom antara Batch 1 dan Batch 2 tidak persis sama (ada yang
 * memakai baris baru, ada kolom yang hanya muncul di salah satu file),
 * sehingga pencocokan kolom dilakukan berdasarkan KATA KUNCI, bukan
 * posisi kolom atau nama header yang persis.
 */
class StartupImporter
{
    /** Peta: nama kolom internal => daftar kata kunci pada header Excel. */
    private const PETA_KOLOM = [
        'nama_ceo'              => ['nama ceo'],
        'tanggal_lahir'         => ['tanggal lahir'],
        'jenis_kelamin'         => ['jenis kelamin'],
        'pendidikan'            => ['pendidikan terakhir'],
        'asal_sekolah'          => ['asal sekolah'],
        'jurusan'               => ['jurusan', 'program studi'],
        'semester'              => ['semester'],
        'tahun_lulus'           => ['tahun lulus'],
        'alamat_rumah'          => ['alamat rumah'],
        'no_wa'                 => ['no kontak', 'whatsapp'],
        'skema_program'         => ['pilihan program'],
        'nama_startup'          => ['nama usaha'],
        'judul_proposal'        => ['judul proposal'],
        'mulai_usaha'           => ['mulai usaha'],
        'tim_inti'              => ['tim inti manajemen'],
        'nama_produk'           => ['nama produk'],
        'deskripsi_produk'      => ['deskripsi produk'],
        'alamat_usaha'          => ['alamat usaha'],
        'bidang_usaha'          => ['bidang usaha'],
        'legalitas_usaha'       => ['legalitas usaha yang dimiliki'],
        'file_legalitas_usaha'  => ['upload berkas legalitas usaha'],
        'legalitas_produk'      => ['legalitas produk yang dimiliki'],
        'file_legalitas_produk' => ['upload berkas legalitas produk'],
        'tk_pria'               => ['tenaga kerja (pria)'],
        'tk_wanita'             => ['tenaga kerja (wanita)'],
        'modal_awal'            => ['modal awal usaha'],
        'sumber_modal'          => ['sumber modal'],
        'asal_invensi'          => ['asal invensi produk'],
        'dosen_pembimbing'      => ['nama dosen'],
        'kapasitas_produksi'    => ['kapasitas produksi'],
        'harga_produk'          => ['harga produk'],
        'jangkauan_pasar'       => ['jangkauan pasar'],
        'omzet_2025'            => ['omzet tahun 2025'],
        'omzet_2026'            => ['omzet jan'],
        'permasalahan'          => ['permasalahan utama'],
        'rencana'               => ['rencana pengembangan'],
        'file_proposal'         => ['upload proposal'],
        'file_foto_ceo'         => ['upload foto ceo'],
        'file_logo'             => ['upload logo'],
        'file_foto_produk'      => ['foto produk'],
        'file_company_profile'  => ['company profile'],
        'file_bmc'              => ['bmc'],
    ];

    /** Peta kolom file => kategori pada tabel dokumentasi. */
    private const PETA_DOKUMEN = [
        'file_proposal'        => 'proposal',
        'file_foto_ceo'        => 'foto_ceo',
        'file_logo'            => 'logo_startup',
        'file_foto_produk'     => 'foto_produk',
        'file_company_profile' => 'company_profile',
        'file_bmc'             => 'bmc',
    ];

    private array $indeks = [];

    private array $catatan = [];

    private int $berhasil = 0;

    private int $gagal = 0;

    /**
     * Jalankan import.
     *
     * @param  array  $baris  Seluruh baris sheet, termasuk baris header.
     */
    public function jalankan(array $baris, string $batch, ?int $tahunProgram, string $namaFile): array
    {
        if (count($baris) < 2) {
            throw new \RuntimeException('Sheet kosong atau tidak punya baris data.');
        }

        $this->indeks = $this->petakanHeader(array_shift($baris));

        if (! isset($this->indeks['nama_startup'], $this->indeks['nama_ceo'])) {
            throw new \RuntimeException(
                'Kolom "Nama Usaha" atau "Nama CEO" tidak ditemukan. Pastikan sheet yang dipilih benar.'
            );
        }

        foreach ($baris as $nomor => $data) {
            $namaStartup = ParserData::teks($this->ambil($data, 'nama_startup'), 200);
            $namaCeo = ParserData::teks($this->ambil($data, 'nama_ceo'), 150);

            // Lewati baris kosong di akhir sheet
            if ($namaStartup === null && $namaCeo === null) {
                continue;
            }

            if ($namaStartup === null || $namaCeo === null) {
                $this->gagal++;
                $this->catatan[] = 'Baris ' . ($nomor + 2) . ': nama usaha atau nama CEO kosong.';
                continue;
            }

            try {
                DB::transaction(fn () => $this->simpanSatuBaris($data, $namaStartup, $namaCeo, $batch, $tahunProgram));
                $this->berhasil++;
            } catch (\Throwable $e) {
                $this->gagal++;
                $this->catatan[] = 'Baris ' . ($nomor + 2) . " ({$namaStartup}): " . $e->getMessage();
            }
        }

        ImportLog::create([
            'nama_file'       => $namaFile,
            'batch'           => $batch,
            'jumlah_berhasil' => $this->berhasil,
            'jumlah_gagal'    => $this->gagal,
            'catatan'         => $this->catatan ? implode("\n", array_slice($this->catatan, 0, 50)) : null,
        ]);

        return [
            'berhasil' => $this->berhasil,
            'gagal'    => $this->gagal,
            'catatan'  => $this->catatan,
        ];
    }

    /** Simpan satu baris Excel menjadi satu startup beserta relasinya. */
    private function simpanSatuBaris(array $data, string $namaStartup, string $namaCeo, string $batch, ?int $tahun): void
    {
        $alamatUsaha = ParserData::teks($this->ambil($data, 'alamat_usaha'));
        $alamatRumah = ParserData::teks($this->ambil($data, 'alamat_rumah'));
        $alamatUntukWilayah = $alamatUsaha ?: $alamatRumah;

        $bidangUsaha = ParserData::teks($this->ambil($data, 'bidang_usaha'), 150);
        $bidangUsahaId = $bidangUsaha
            ? BidangUsaha::firstOrCreate(['nama_bidang' => $bidangUsaha])->id
            : null;

        $omzet2025 = $this->ambil($data, 'omzet_2025');
        $omzet2026 = $this->ambil($data, 'omzet_2026');

        // Dua titik omzet disimpan TERPISAH karena panjang periodenya berbeda:
        //   baseline    = Omzet Tahun 2025      (12 bulan)
        //   pembanding  = Omzet Jan-Maret 2026  (3 bulan)
        $omsetAwal = ParserData::uang($omzet2025);
        $omsetPembanding = ParserData::uang($omzet2026);

        // Satu startup dianggap sama jika nama usaha + nama CEO sama,
        // supaya import ulang tidak menghasilkan data ganda.
        $startup = Startup::updateOrCreate(
            ['nama_startup' => $namaStartup, 'nama_ceo' => $namaCeo],
            [
                'batch'                 => $batch,
                'tahun_program'         => $tahun,
                'skema_program'         => ParserData::teks($this->ambil($data, 'skema_program'), 50),

                'tanggal_lahir_ceo'     => ParserData::tanggal($this->ambil($data, 'tanggal_lahir')),
                'jenis_kelamin_ceo'     => ParserData::gender($this->ambil($data, 'jenis_kelamin')) ?? 'L',
                'pendidikan_terakhir'   => ParserData::teks($this->ambil($data, 'pendidikan'), 50),
                'asal_sekolah'          => ParserData::teks($this->ambil($data, 'asal_sekolah'), 200),
                'jurusan'               => ParserData::teks($this->ambil($data, 'jurusan'), 200),
                'semester'              => ParserData::teks($this->ambil($data, 'semester'), 20),
                'tahun_lulus'           => ParserData::teks($this->ambil($data, 'tahun_lulus'), 10),

                'alamat_rumah'          => $alamatRumah,
                'alamat_usaha'          => $alamatUsaha,
                'kota'                  => ParserData::kota($alamatUntukWilayah),
                'provinsi'              => ParserData::provinsi($alamatUntukWilayah),
                'no_wa'                 => ParserData::teks($this->ambil($data, 'no_wa'), 40),

                'bidang_usaha_id'       => $bidangUsahaId,
                'mulai_usaha'           => ParserData::tanggal($this->ambil($data, 'mulai_usaha')),
                'nama_produk'           => ParserData::teks($this->ambil($data, 'nama_produk'), 255),
                'deskripsi_produk'      => ParserData::teks($this->ambil($data, 'deskripsi_produk')),
                'judul_proposal'        => ParserData::teks($this->ambil($data, 'judul_proposal')),

                'asal_invensi'          => ParserData::asalInvensi($this->ambil($data, 'asal_invensi')),
                'keterangan_invensi'    => ParserData::teks($this->ambil($data, 'asal_invensi')),
                'nama_dosen_pembimbing' => ParserData::teks($this->ambil($data, 'dosen_pembimbing'), 255),

                'tenaga_kerja_l'        => ParserData::bilangan($this->ambil($data, 'tk_pria')),
                'tenaga_kerja_p'        => ParserData::bilangan($this->ambil($data, 'tk_wanita')),

                'modal_awal'            => ParserData::uang($this->ambil($data, 'modal_awal')),
                'modal_awal_teks'       => ParserData::teks($this->ambil($data, 'modal_awal'), 255),
                'sumber_modal'          => ParserData::teks($this->ambil($data, 'sumber_modal'), 100),

                'kapasitas_produksi'    => ParserData::teks($this->ambil($data, 'kapasitas_produksi')),
                'harga_produk'          => ParserData::teks($this->ambil($data, 'harga_produk')),
                'jangkauan_pasar'       => ParserData::teks($this->ambil($data, 'jangkauan_pasar'), 100),

                'omset_awal'               => $omsetAwal,
                'omset_awal_teks'          => ParserData::teks($omzet2025, 255),
                'periode_omset_awal'       => $omsetAwal !== null ? 'Tahun 2025' : null,
                'bulan_periode_awal'       => $omsetAwal !== null ? 12 : null,

                'omset_pembanding'         => $omsetPembanding,
                'omset_pembanding_teks'    => ParserData::teks($omzet2026, 255),
                'periode_omset_pembanding' => $omsetPembanding !== null ? 'Jan-Mar 2026' : null,
                'bulan_periode_pembanding' => $omsetPembanding !== null ? 3 : null,

                'permasalahan_utama'    => ParserData::teks($this->ambil($data, 'permasalahan')),
                'rencana_pengembangan'  => ParserData::teks($this->ambil($data, 'rencana')),

                'status'                => 'aktif',
            ]
        );

        // Relasi ditulis ulang setiap import agar tidak menumpuk saat import ulang.
        $this->simpanLegalitas($startup, $data);
        $this->simpanAnggotaTim($startup, $data);
        $this->simpanDokumentasi($startup, $data);
    }

    private function simpanLegalitas(Startup $startup, array $data): void
    {
        Legalitas::where('startup_id', $startup->id)->delete();

        $pasangan = [
            'usaha'  => ['legalitas_usaha', 'file_legalitas_usaha'],
            'produk' => ['legalitas_produk', 'file_legalitas_produk'],
        ];

        foreach ($pasangan as $tipe => [$kolomNama, $kolomFile]) {
            $daftar = ParserData::legalitas($this->ambil($data, $kolomNama));
            $file = ParserData::teks($this->ambil($data, $kolomFile), 500);

            foreach ($daftar as $nama) {
                Legalitas::create([
                    'startup_id' => $startup->id,
                    'tipe'       => $tipe,
                    'nama'       => mb_substr($nama, 0, 150),
                    'file'       => $file,
                ]);
            }
        }
    }

    private function simpanAnggotaTim(Startup $startup, array $data): void
    {
        AnggotaTim::where('startup_id', $startup->id)->delete();

        foreach (ParserData::anggotaTim($this->ambil($data, 'tim_inti')) as $anggota) {
            AnggotaTim::create([
                'startup_id'    => $startup->id,
                'nama'          => $anggota['nama'],
                'jabatan'       => $anggota['jabatan'],
                'jenis_kelamin' => $anggota['jenis_kelamin'],
            ]);
        }
    }

    private function simpanDokumentasi(Startup $startup, array $data): void
    {
        Dokumentasi::where('startup_id', $startup->id)->delete();

        foreach (self::PETA_DOKUMEN as $kolom => $kategori) {
            $file = ParserData::teks($this->ambil($data, $kolom), 500);

            if ($file === null || ! str_starts_with(strtolower($file), 'http')) {
                continue;
            }

            Dokumentasi::create([
                'startup_id' => $startup->id,
                'kategori'   => $kategori,
                'judul'      => null,
                'file'       => $file,
            ]);
        }
    }

    /**
     * Cocokkan header Excel dengan kolom internal berdasarkan kata kunci.
     *
     * @return array<string,int> nama kolom internal => indeks kolom
     */
    private function petakanHeader(array $header): array
    {
        $indeks = [];

        foreach ($header as $posisi => $judul) {
            if (blank($judul)) {
                continue;
            }

            // Normalisasi: huruf kecil, baris baru & spasi ganda jadi satu spasi
            $bersih = strtolower(trim(preg_replace('/\s+/u', ' ', (string) $judul)));

            foreach (self::PETA_KOLOM as $kolom => $kataKunci) {
                if (isset($indeks[$kolom])) {
                    continue; // kolom pertama yang cocok yang dipakai
                }

                foreach ($kataKunci as $kunci) {
                    if (str_contains($bersih, $kunci)) {
                        $indeks[$kolom] = $posisi;
                        break 2;
                    }
                }
            }
        }

        return $indeks;
    }

    /** Ambil nilai satu kolom dari baris data. */
    private function ambil(array $baris, string $kolom)
    {
        if (! isset($this->indeks[$kolom])) {
            return null;
        }

        return $baris[$this->indeks[$kolom]] ?? null;
    }
}
