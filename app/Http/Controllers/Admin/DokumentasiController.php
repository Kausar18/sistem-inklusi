<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Startup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    private const MIME_DIIZINKAN = 'jpg,jpeg,png,pdf,doc,docx,xls,xlsx';

    private const KATEGORI = [
        'foto_ceo', 'logo_startup', 'foto_produk', 'company_profile',
        'bmc', 'proposal', 'infografis', 'lainnya',
    ];

    public function store(Request $request, Startup $startup): RedirectResponse
    {
        $data = $request->validate([
            'kategori' => ['required', 'in:' . implode(',', self::KATEGORI)],
            'judul'    => ['nullable', 'string', 'max:255'],
            'link'     => ['nullable', 'url', 'max:500'],
            'berkas'   => ['nullable', 'file', 'max:10240', 'mimes:' . self::MIME_DIIZINKAN],
        ]);

        $file = $this->tentukanFile($request, 'dokumentasi');

        if (blank($file)) {
            return back()
                ->withErrors(['berkas' => 'Isi tautan atau unggah berkas.'])
                ->withFragment('dokumentasi');
        }

        $startup->dokumentasi()->create([
            'kategori' => $data['kategori'],
            'judul'    => $data['judul'] ?? null,
            'file'     => $file,
        ]);

        return back()->with('sukses', 'Dokumentasi ditambahkan.')->withFragment('dokumentasi');
    }

    public function destroy(Startup $startup, Dokumentasi $dokumentasi): RedirectResponse
    {
        abort_unless($dokumentasi->startup_id === $startup->id, 404);

        $dokumentasi->delete();

        return back()->with('sukses', 'Dokumentasi dihapus.')->withFragment('dokumentasi');
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
