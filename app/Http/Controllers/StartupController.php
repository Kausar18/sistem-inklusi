<?php

namespace App\Http\Controllers;

use App\Models\BidangUsaha;
use App\Models\Startup;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StartupController extends Controller
{
    /** Daftar startup dengan pencarian, filter, dan ringkasan statistik. */
    public function index(Request $request): View
    {
        // Query dasar yang sudah difilter - dipakai untuk statistik
        // maupun untuk daftar berhalaman.
        $terfilter = fn () => Startup::query()
            ->cari($request->input('q'))
            ->wilayah($request->input('kota'))
            ->bidang($request->input('bidang'))
            ->batch($request->input('batch'))
            ->asalInvensi($request->input('invensi'))
            ->genderCeo($request->input('gender'))
            ->rentangOmset(
                $this->keAngka($request->input('omset_min')),
                $this->keAngka($request->input('omset_max'))
            );

        $statistik = [
            'jumlah' => (clone $terfilter())->count(),
            'omset' => (clone $terfilter())->sum('omset_awal'),
            'tenaga_l' => (clone $terfilter())->sum('tenaga_kerja_l'),
            'tenaga_p' => (clone $terfilter())->sum('tenaga_kerja_p'),
            'wilayah' => (clone $terfilter())->whereNotNull('kota')->distinct()->count('kota'),
            'omset_maks' => (clone $terfilter())->max('omset_awal') ?: 0,
            'invensi_ipb' => (clone $terfilter())->whereIn('asal_invensi', ['IPB', 'Kombinasi'])->count(),
        ];

        $startups = $terfilter()
            ->with([
                'bidangUsaha',
                'dokumentasi' => fn ($q) => $q->where('kategori', 'logo_startup'),
            ])
            ->orderBy($this->kolomUrut($request->input('urut')), $this->arahUrut($request->input('urut')))
            ->paginate(12)
            ->withQueryString();

        return view('startup.index', [
            'startups' => $startups,
            'statistik' => $statistik,
            'daftarBidang' => BidangUsaha::orderBy('nama_bidang')->get(),
            'daftarKota' => Startup::whereNotNull('kota')->distinct()->orderBy('kota')->pluck('kota'),
            'daftarBatch' => Startup::whereNotNull('batch')->distinct()->orderBy('batch')->pluck('batch'),
            'totalSemua' => Startup::count(),
            'adaFilter' => $request->hasAny(['q', 'kota', 'bidang', 'batch', 'invensi', 'gender', 'omset_min', 'omset_max']),
        ]);
    }

    /** Halaman detail satu startup beserta seluruh relasinya. */
    public function show(Startup $startup): View
    {
        $startup->load([
            'bidangUsaha',
            'anggotaTim',
            'legalitas',
            'dokumentasi',
            'targetOutput',
            'pendampingan' => fn ($q) => $q->orderByDesc('tanggal'),
            'monitoring' => fn ($q) => $q->orderByDesc('tanggal'),
        ]);

        // Titik "AFTER" diambil dari catatan monitoring terbaru.
        $terbaru = $startup->monitoring->first();

        $perbandingan = [
            'omset' => [
                'label' => 'Omset',
                'before' => (float) $startup->omset_awal,
                'after' => $terbaru?->omzet !== null ? (float) $terbaru->omzet : null,
                'format' => 'rupiah',
            ],
            'tenaga_kerja' => [
                'label' => 'Tenaga kerja',
                'before' => (float) $startup->total_tenaga_kerja,
                'after' => $terbaru && $terbaru->tenaga_kerja_l !== null
                                ? (float) $terbaru->total_tenaga_kerja
                                : null,
                'format' => 'angka',
            ],
            'jangkauan_pasar' => [
                'label' => 'Jangkauan pasar',
                'before' => $startup->jangkauan_pasar,
                'after' => $terbaru?->wilayah_penjualan,
                'format' => 'teks',
            ],
        ];

        return view('startup.show', [
            'startup' => $startup,
            'logo' => $startup->dokumentasi->firstWhere('kategori', 'logo_startup'),
            'fotoCeo' => $startup->dokumentasi->firstWhere('kategori', 'foto_ceo'),
            'fotoProduk' => $startup->dokumentasi->where('kategori', 'foto_produk'),
            // Berkas non-gambar: proposal, company profile, BMC, dll
            'berkas' => $startup->dokumentasi->whereNotIn('kategori', [
                'logo_startup', 'foto_ceo', 'foto_produk',
            ]),
            'terbaru' => $terbaru,
            'perbandingan' => $perbandingan,
        ]);
    }

    /** Halaman infografis profil startup, siap dicetak atau disimpan sebagai PDF. */
    public function infografis(Startup $startup): View
    {
        $startup->load(['bidangUsaha', 'anggotaTim', 'legalitas', 'dokumentasi']);

        return view('startup.infografis', [
            'startup' => $startup,
            'logo' => $startup->dokumentasi->firstWhere('kategori', 'logo_startup'),
            'fotoProduk' => $startup->dokumentasi->where('kategori', 'foto_produk'),
            // Ringkas nilai rupiah menjadi "Rp 1,4 M" atau "Rp 486 Jt"
            'ringkas' => function ($nilai) {
                if ($nilai >= 1_000_000_000) {
                    return 'Rp '.number_format($nilai / 1_000_000_000, 1, ',', '.').' M';
                }

                return 'Rp '.number_format($nilai / 1_000_000, 0, ',', '.').' Jt';
            },
        ]);
    }

    /** Terima input omset dalam bentuk "500.000.000" maupun "500000000". */
    private function keAngka(?string $nilai): ?float
    {
        if (blank($nilai)) {
            return null;
        }

        $bersih = preg_replace('/[^\d]/', '', $nilai);

        return $bersih === '' ? null : (float) $bersih;
    }

    private function kolomUrut(?string $urut): string
    {
        return match ($urut) {
            'omset_tertinggi', 'omset_terendah' => 'omset_awal',
            'tenaga_kerja' => 'tenaga_kerja_l',
            default => 'nama_startup',
        };
    }

    private function arahUrut(?string $urut): string
    {
        return in_array($urut, ['omset_tertinggi', 'tenaga_kerja'], true) ? 'desc' : 'asc';
    }
}
