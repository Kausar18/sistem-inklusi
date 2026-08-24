<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StartupRequest;
use App\Models\BidangUsaha;
use App\Models\Startup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StartupController extends Controller
{
    /** Daftar startup untuk dikelola, memakai scope pencarian/filter yang sama dengan halaman publik. */
    public function index(Request $request): View
    {
        $startups = Startup::query()
            ->cari($request->input('q'))
            ->wilayah($request->input('kota'))
            ->bidang($request->input('bidang'))
            ->batch($request->input('batch'))
            ->with('bidangUsaha')
            ->orderBy('nama_startup')
            ->paginate(20)
            ->withQueryString();

        return view('admin.startup.index', [
            'startups'     => $startups,
            'daftarBidang' => BidangUsaha::orderBy('nama_bidang')->get(),
            'daftarBatch'  => Startup::whereNotNull('batch')->distinct()->orderBy('batch')->pluck('batch'),
        ]);
    }

    public function create(): View
    {
        return view('admin.startup.create', [
            'startup'      => new Startup(),
            'daftarBidang' => BidangUsaha::orderBy('nama_bidang')->get(),
        ]);
    }

    public function store(StartupRequest $request): RedirectResponse
    {
        $startup = Startup::create($this->siapkanData($request));

        return redirect()
            ->route('admin.startup.edit', $startup)
            ->with('sukses', 'Startup baru tersimpan. Silakan lengkapi anggota tim, legalitas, dan berkas lain di bawah.');
    }

    public function edit(Startup $startup): View
    {
        $startup->load(['bidangUsaha', 'anggotaTim', 'legalitas', 'dokumentasi', 'targetOutput']);

        return view('admin.startup.edit', [
            'startup'      => $startup,
            'daftarBidang' => BidangUsaha::orderBy('nama_bidang')->get(),
        ]);
    }

    public function update(StartupRequest $request, Startup $startup): RedirectResponse
    {
        $startup->update($this->siapkanData($request));

        return back()->with('sukses', 'Profil startup tersimpan.');
    }

    public function destroy(Startup $startup): RedirectResponse
    {
        $startup->delete();

        return redirect()
            ->route('admin.startup.index')
            ->with('sukses', "Startup \"{$startup->nama_startup}\" dan seluruh data terkaitnya telah dihapus.");
    }

    /** Siapkan data tervalidasi, termasuk konversi nilai rupiah berformat bebas menjadi angka. */
    private function siapkanData(StartupRequest $request): array
    {
        $data = $request->validated();

        foreach (['modal_awal', 'omset_awal', 'omset_pembanding'] as $kolom) {
            $mentah = $data[$kolom] ?? null;
            $data["{$kolom}_teks"] = blank($mentah) ? null : $mentah;
            $data[$kolom] = $this->keAngka($mentah);
        }

        return $data;
    }

    /** Terima input rupiah dalam bentuk "500.000.000" maupun "500000000". */
    private function keAngka(?string $nilai): ?float
    {
        if (blank($nilai)) {
            return null;
        }

        $bersih = preg_replace('/[^\d]/', '', $nilai);

        return $bersih === '' ? null : (float) $bersih;
    }
}
