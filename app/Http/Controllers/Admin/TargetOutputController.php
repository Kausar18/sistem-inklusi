<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Startup;
use App\Models\TargetOutput;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TargetOutputController extends Controller
{
    public function store(Request $request, Startup $startup): RedirectResponse
    {
        $data = $request->validate([
            'nama_target' => ['required', 'string', 'max:255'],
            'status'      => ['required', 'in:belum_tercapai,proses,tercapai'],
            'keterangan'  => ['nullable', 'string'],
        ], [], [
            'nama_target' => 'nama target',
        ]);

        $startup->targetOutput()->create($data);

        return back()->with('sukses', 'Target output ditambahkan.')->withFragment('target');
    }

    public function destroy(Startup $startup, TargetOutput $targetOutput): RedirectResponse
    {
        abort_unless($targetOutput->startup_id === $startup->id, 404);

        $targetOutput->delete();

        return back()->with('sukses', 'Target output dihapus.')->withFragment('target');
    }
}
