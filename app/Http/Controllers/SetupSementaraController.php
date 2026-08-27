<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Route sementara untuk membuat akun admin pertama di server yang tidak
 * punya akses Shell/SSH (misalnya Render plan Free).
 *
 * HAPUS controller dan route ini setelah dipakai — ini bukan bagian
 * permanen dari aplikasi, cuma jalan pintas satu kali saat setup awal.
 */
class SetupSementaraController extends Controller
{
    public function create(Request $request, string $token): View|RedirectResponse
    {
        $this->pastikanTokenValid($token);

        return view('setup-sementara.buat-admin');
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $this->pastikanTokenValid($token);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create($data);

        return redirect()->route('login')->with('sukses', 'Akun admin berhasil dibuat. Silakan masuk.');
    }

    public function formHapusStartup(Request $request, string $token): View
    {
        $this->pastikanTokenValid($token);

        return view('setup-sementara.hapus-startup');
    }

    /**
     * Hapus SEMUA data startup (dan relasinya, lewat cascade delete) —
     * dipakai sekali untuk membersihkan data yang salah ter-import
     * sebelum fitur pemilihan sheet diperbaiki.
     */
    public function hapusSemuaStartup(Request $request, string $token): RedirectResponse
    {
        $this->pastikanTokenValid($token);

        if ($request->input('konfirmasi') !== 'HAPUS') {
            return back()->withErrors(['konfirmasi' => 'Ketik HAPUS (huruf besar semua) untuk konfirmasi.']);
        }

        $jumlah = Startup::count();
        Startup::query()->delete();

        return redirect()->route('admin.import.create')
            ->with('sukses', "{$jumlah} data startup berhasil dihapus. Silakan import ulang dengan sheet yang benar.");
    }

    private function pastikanTokenValid(string $token): void
    {
        $rahasia = env('SETUP_TOKEN');

        abort_if(blank($rahasia) || ! hash_equals($rahasia, $token), 404);
    }
}
