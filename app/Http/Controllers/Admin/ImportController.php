<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StartupImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    private const DISK = 'local';

    private const FOLDER = 'import-sementara';

    public function create(): View
    {
        return view('admin.import.create');
    }

    /** Langkah 1: terima berkas, lalu minta pengguna pilih sheet mana yang benar. */
    public function store(Request $request): View|RedirectResponse
    {
        $data = $request->validate([
            'berkas' => ['required', 'file', 'mimes:xlsx,xls'],
            'batch' => ['nullable', 'string', 'max:20'],
            'tahun' => ['nullable', 'integer', 'digits:4'],
        ], [], [
            'berkas' => 'berkas Excel',
        ]);

        $daftarSheet = IOFactory::createReaderForFile($data['berkas']->getRealPath())
            ->listWorksheetNames($data['berkas']->getRealPath());

        // Simpan sementara supaya bisa dibaca lagi di langkah konfirmasi
        // (upload berkas cuma tersedia selama satu request).
        $namaAsli = $data['berkas']->getClientOriginalName();
        $pathSementara = $data['berkas']->store(self::FOLDER, self::DISK);

        if (count($daftarSheet) === 1) {
            // Cuma satu sheet — tidak perlu tanya, langsung impor.
            return $this->jalankanImpor($pathSementara, $daftarSheet[0], $namaAsli, $data['batch'] ?? null, $data['tahun'] ?? null);
        }

        return view('admin.import.pilih-sheet', [
            'daftarSheet' => $daftarSheet,
            'pathSementara' => $pathSementara,
            'namaAsli' => $namaAsli,
            'batch' => $data['batch'] ?? null,
            'tahun' => $data['tahun'] ?? null,
        ]);
    }

    /** Langkah 2 (hanya kalau berkas punya >1 sheet): impor sheet yang dipilih. */
    public function proses(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'path_sementara' => ['required', 'string'],
            'nama_asli' => ['required', 'string'],
            'sheet' => ['required', 'string'],
            'batch' => ['nullable', 'string', 'max:20'],
            'tahun' => ['nullable', 'integer', 'digits:4'],
        ]);

        abort_unless(Storage::disk(self::DISK)->exists($data['path_sementara']), 404);

        return $this->jalankanImpor(
            $data['path_sementara'],
            $data['sheet'],
            $data['nama_asli'],
            $data['batch'] ?? null,
            $data['tahun'] ?? null
        );
    }

    private function jalankanImpor(
        string $pathSementara,
        string $sheet,
        string $namaAsli,
        ?string $batch,
        ?int $tahun
    ): RedirectResponse {
        $pathAsli = Storage::disk(self::DISK)->path($pathSementara);

        $reader = IOFactory::createReaderForFile($pathAsli);
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly([$sheet]);

        $spreadsheet = $reader->load($pathAsli);
        $baris = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        Storage::disk(self::DISK)->delete($pathSementara);

        try {
            $hasil = app(StartupImporter::class)->jalankan(
                $baris,
                $batch ?: 'Tanpa Batch',
                $tahun,
                $namaAsli
            );
        } catch (\Throwable $e) {
            return redirect()->route('admin.import.create')
                ->withErrors(['berkas' => 'Import gagal: '.$e->getMessage()]);
        }

        $pesan = "Berhasil impor {$hasil['berhasil']} startup dari sheet \"{$sheet}\".";

        if ($hasil['gagal'] > 0) {
            $pesan .= " {$hasil['gagal']} baris gagal — lihat detail di bawah.";
        }

        return redirect()->route('admin.import.create')
            ->with('sukses', $pesan)
            ->with('catatanGagal', $hasil['catatan'] ?? []);
    }
}
