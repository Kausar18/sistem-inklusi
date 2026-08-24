<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StartupImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    public function create(): View
    {
        return view('admin.import.create');
    }

    public function store(Request $request, StartupImporter $importer): RedirectResponse
    {
        $data = $request->validate([
            'berkas' => ['required', 'file', 'mimes:xlsx,xls'],
            'batch'  => ['nullable', 'string', 'max:20'],
            'tahun'  => ['nullable', 'integer', 'digits:4'],
        ], [], [
            'berkas' => 'berkas Excel',
        ]);

        $path = $data['berkas']->getRealPath();

        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);

        $daftarSheet = $reader->listWorksheetNames($path);
        $reader->setLoadSheetsOnly([$daftarSheet[0]]);

        $spreadsheet = $reader->load($path);
        $baris = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        try {
            $hasil = $importer->jalankan(
                $baris,
                $data['batch'] ?: 'Tanpa Batch',
                $data['tahun'] ? (int) $data['tahun'] : null,
                $data['berkas']->getClientOriginalName()
            );
        } catch (\Throwable $e) {
            return back()->withErrors(['berkas' => 'Import gagal: ' . $e->getMessage()]);
        }

        $pesan = "Berhasil impor {$hasil['berhasil']} startup dari sheet \"{$daftarSheet[0]}\".";

        if ($hasil['gagal'] > 0) {
            $pesan .= " {$hasil['gagal']} baris gagal — lihat detail di bawah.";
        }

        return back()
            ->with('sukses', $pesan)
            ->with('catatanGagal', $hasil['catatan'] ?? []);
    }
}
