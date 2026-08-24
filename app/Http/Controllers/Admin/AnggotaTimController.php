<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaTim;
use App\Models\Startup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnggotaTimController extends Controller
{
    public function store(Request $request, Startup $startup): RedirectResponse
    {
        $data = $request->validate([
            'nama'           => ['required', 'string', 'max:150'],
            'jabatan'        => ['nullable', 'string', 'max:100'],
            'jenis_kelamin'  => ['nullable', 'in:L,P'],
        ], [], [
            'nama'          => 'nama anggota',
            'jenis_kelamin' => 'jenis kelamin',
        ]);

        $startup->anggotaTim()->create($data);

        return back()->with('sukses', 'Anggota tim ditambahkan.')->withFragment('tim');
    }

    public function destroy(Startup $startup, AnggotaTim $anggotaTim): RedirectResponse
    {
        abort_unless($anggotaTim->startup_id === $startup->id, 404);

        $anggotaTim->delete();

        return back()->with('sukses', 'Anggota tim dihapus.')->withFragment('tim');
    }
}
