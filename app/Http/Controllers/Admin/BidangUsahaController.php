<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BidangUsaha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BidangUsahaController extends Controller
{
    public function index(): View
    {
        return view('admin.bidang-usaha.index', [
            'daftarBidang' => BidangUsaha::withCount('startups')->orderBy('nama_bidang')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_bidang' => ['required', 'string', 'max:150', 'unique:bidang_usaha,nama_bidang'],
        ], [], [
            'nama_bidang' => 'nama bidang usaha',
        ]);

        BidangUsaha::create($data);

        return back()->with('sukses', 'Bidang usaha ditambahkan.');
    }

    public function update(Request $request, BidangUsaha $bidangUsaha): RedirectResponse
    {
        $data = $request->validate([
            'nama_bidang' => ['required', 'string', 'max:150', 'unique:bidang_usaha,nama_bidang,'.$bidangUsaha->id],
        ], [], [
            'nama_bidang' => 'nama bidang usaha',
        ]);

        $bidangUsaha->update($data);

        return back()->with('sukses', 'Bidang usaha diperbarui.');
    }

    public function destroy(BidangUsaha $bidangUsaha): RedirectResponse
    {
        if ($bidangUsaha->startups()->exists()) {
            return back()->with('gagal', "Bidang usaha \"{$bidangUsaha->nama_bidang}\" masih dipakai oleh startup dan tidak bisa dihapus.");
        }

        $bidangUsaha->delete();

        return back()->with('sukses', 'Bidang usaha dihapus.');
    }
}
