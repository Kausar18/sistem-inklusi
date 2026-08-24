<?php

namespace App\Http\Controllers;

use App\Models\Pendampingan;
use App\Models\Startup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PendampinganController extends Controller
{
    /** Simpan satu kegiatan pendampingan untuk sebuah startup. */
    public function store(Request $request, Startup $startup): RedirectResponse
    {
        $data = $request->validate([
            'jenis'      => ['required', 'in:training,mentoring,business_matching,form_pendaftaran,lainnya'],
            'tanggal'    => ['required', 'date'],
            'pendamping' => ['nullable', 'string', 'max:150'],
            'lokasi'     => ['nullable', 'string', 'max:255'],
            'catatan'    => ['nullable', 'string', 'max:5000'],
        ], [], [
            'jenis'      => 'jenis kegiatan',
            'tanggal'    => 'tanggal kegiatan',
            'pendamping' => 'nama pendamping',
        ]);

        $startup->pendampingan()->create($data);

        return back()
            ->with('sukses', 'Kegiatan pendampingan tersimpan.')
            ->withFragment('pendampingan');
    }

    /** Hapus satu catatan pendampingan. */
    public function destroy(Startup $startup, Pendampingan $pendampingan): RedirectResponse
    {
        abort_unless($pendampingan->startup_id === $startup->id, 404);

        $pendampingan->delete();

        return back()
            ->with('sukses', 'Catatan pendampingan dihapus.')
            ->withFragment('pendampingan');
    }
}
