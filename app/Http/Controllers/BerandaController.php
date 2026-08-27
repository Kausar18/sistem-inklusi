<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use Illuminate\View\View;

class BerandaController extends Controller
{
    /**
     * Halaman beranda program.
     *
     * Sengaja hanya menampilkan sedikit angka sebagai pelengkap narasi.
     * Statistik lengkap ada di halaman tersendiri.
     */
    public function index(): View
    {
        return view('beranda', [
            'angka' => [
                'startup' => Startup::count(),
                'tenaga' => (int) Startup::sum('tenaga_kerja_l') + (int) Startup::sum('tenaga_kerja_p'),
                'wilayah' => Startup::whereNotNull('kota')->distinct()->count('kota'),
                'bidang' => Startup::whereNotNull('bidang_usaha_id')->distinct()->count('bidang_usaha_id'),
            ],
        ]);
    }
}
