<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Legalitas;
use App\Models\Startup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LegalitasController extends Controller
{
    private const MIME_DIIZINKAN = 'jpg,jpeg,png,pdf,doc,docx,xls,xlsx';

    public function store(Request $request, Startup $startup): RedirectResponse
    {
        $data = $request->validate([
            'tipe'   => ['required', 'in:usaha,produk'],
            'nama'   => ['required', 'string', 'max:150'],
            'link'   => ['nullable', 'url', 'max:500'],
            'berkas' => ['nullable', 'file', 'max:10240', 'mimes:' . self::MIME_DIIZINKAN],
        ]);

        $startup->legalitas()->create([
            'tipe' => $data['tipe'],
            'nama' => $data['nama'],
            'file' => $this->tentukanFile($request, 'legalitas'),
        ]);

        return back()->with('sukses', 'Legalitas ditambahkan.')->withFragment('legalitas');
    }

    public function destroy(Startup $startup, Legalitas $legalitas): RedirectResponse
    {
        abort_unless($legalitas->startup_id === $startup->id, 404);

        $legalitas->delete();

        return back()->with('sukses', 'Legalitas dihapus.')->withFragment('legalitas');
    }

    /** Berkas boleh diisi salah satu: link eksternal, atau upload berkas. */
    private function tentukanFile(Request $request, string $folder): ?string
    {
        if ($request->hasFile('berkas')) {
            return Storage::disk('public')->putFile($folder, $request->file('berkas'));
        }

        return $request->input('link') ?: null;
    }
}
