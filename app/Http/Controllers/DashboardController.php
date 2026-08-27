<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Halaman ringkasan: statistik utama dan grafik sebaran. */
    public function index(): View
    {
        return view('dashboard', [
            'ringkasan' => $this->ringkasan(),
            'bidangUsaha' => $this->sebaranBidangUsaha(),
            'wilayah' => $this->sebaranWilayah(),
            'batch' => $this->perbandinganBatch(),
            'genderTenaga' => $this->komposisiTenagaKerja(),
            'genderCeo' => $this->komposisiGenderCeo(),
            'asalInvensi' => $this->sebaranAsalInvensi(),
            'jangkauanPasar' => $this->sebaranJangkauanPasar(),
            'omsetTeratas' => $this->omsetTeratas(),
        ]);
    }

    /** Angka utama untuk kartu statistik. */
    private function ringkasan(): array
    {
        return [
            'startup' => Startup::count(),
            'omset' => (float) Startup::sum('omset_awal'),
            'tenaga_l' => (int) Startup::sum('tenaga_kerja_l'),
            'tenaga_p' => (int) Startup::sum('tenaga_kerja_p'),
            'wilayah' => Startup::whereNotNull('kota')->distinct()->count('kota'),
            'bidang' => Startup::whereNotNull('bidang_usaha_id')->distinct()->count('bidang_usaha_id'),
            'invensi_ipb' => Startup::whereIn('asal_invensi', ['IPB', 'Kombinasi'])->count(),
            'ceo_p' => Startup::where('jenis_kelamin_ceo', 'P')->count(),
            'monitoring' => DB::table('monitoring')->count(),
        ];
    }

    /** 10 bidang usaha dengan startup terbanyak. */
    private function sebaranBidangUsaha(): array
    {
        return Startup::query()
            ->join('bidang_usaha', 'startups.bidang_usaha_id', '=', 'bidang_usaha.id')
            ->select('bidang_usaha.nama_bidang', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('bidang_usaha.nama_bidang')
            ->orderByDesc('jumlah')
            ->limit(10)
            ->pluck('jumlah', 'nama_bidang')
            ->all();
    }

    /** 10 wilayah dengan startup terbanyak. */
    private function sebaranWilayah(): array
    {
        return Startup::query()
            ->whereNotNull('kota')
            ->select('kota', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('kota')
            ->orderByDesc('jumlah')
            ->limit(10)
            ->pluck('jumlah', 'kota')
            ->all();
    }

    /** Perbandingan antar batch: jumlah startup, omset, dan tenaga kerja. */
    private function perbandinganBatch(): array
    {
        return Startup::query()
            ->whereNotNull('batch')
            ->select(
                'batch',
                DB::raw('COUNT(*) as jumlah'),
                DB::raw('COALESCE(SUM(omset_awal), 0) as omset'),
                DB::raw('COALESCE(SUM(tenaga_kerja_l + tenaga_kerja_p), 0) as tenaga')
            )
            ->groupBy('batch')
            ->orderBy('batch')
            ->get()
            ->map(fn ($b) => [
                'batch' => $b->batch,
                'jumlah' => (int) $b->jumlah,
                'omset' => (float) $b->omset,
                'tenaga' => (int) $b->tenaga,
            ])
            ->all();
    }

    /** Komposisi tenaga kerja laki-laki dan perempuan. */
    private function komposisiTenagaKerja(): array
    {
        return [
            'Laki-laki' => (int) Startup::sum('tenaga_kerja_l'),
            'Perempuan' => (int) Startup::sum('tenaga_kerja_p'),
        ];
    }

    /** Komposisi jenis kelamin CEO. */
    private function komposisiGenderCeo(): array
    {
        return [
            'Laki-laki' => Startup::where('jenis_kelamin_ceo', 'L')->count(),
            'Perempuan' => Startup::where('jenis_kelamin_ceo', 'P')->count(),
        ];
    }

    /** Sebaran asal invensi produk. */
    private function sebaranAsalInvensi(): array
    {
        return Startup::query()
            ->select('asal_invensi', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('asal_invensi')
            ->orderByDesc('jumlah')
            ->pluck('jumlah', 'asal_invensi')
            ->all();
    }

    /** Sebaran jangkauan pasar. */
    private function sebaranJangkauanPasar(): array
    {
        return Startup::query()
            ->whereNotNull('jangkauan_pasar')
            ->select('jangkauan_pasar', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('jangkauan_pasar')
            ->orderByDesc('jumlah')
            ->pluck('jumlah', 'jangkauan_pasar')
            ->all();
    }

    /** 8 startup dengan omset awal tertinggi. */
    private function omsetTeratas()
    {
        return Startup::query()
            ->where('omset_awal', '>', 0)
            ->orderByDesc('omset_awal')
            ->limit(8)
            ->get(['id', 'nama_startup', 'omset_awal', 'kota']);
    }
}
