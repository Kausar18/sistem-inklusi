<?php

namespace App\Console\Commands;

use App\Services\StartupImporter;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Import data tenant dari file Excel ke database.
 *
 * Contoh pemakaian:
 *   php artisan startup:import "storage/app/batch1.xlsx" --list
 *   php artisan startup:import "storage/app/batch1.xlsx" --sheet="Data 37 Lolos" --batch="Batch 1" --tahun=2026
 */
class ImportStartupCommand extends Command
{
    protected $signature = 'startup:import
                            {file : Path file Excel (relatif terhadap folder project atau absolut)}
                            {--sheet= : Nama sheet yang akan diimport}
                            {--batch= : Label batch, mis. "Batch 1"}
                            {--tahun= : Tahun program, mis. 2026}
                            {--list : Hanya tampilkan daftar sheet, tidak melakukan import}';

    protected $description = 'Import data profil startup dari file Excel Rekap Database Tenant';

    public function handle(StartupImporter $importer): int
    {
        $file = $this->argument('file');

        if (! file_exists($file)) {
            $file = base_path($file);
        }

        if (! file_exists($file)) {
            $this->error('File tidak ditemukan: '.$this->argument('file'));

            return self::FAILURE;
        }

        $reader = IOFactory::createReaderForFile($file);
        $daftarSheet = $reader->listWorksheetNames($file);

        // Mode --list: tampilkan nama sheet lalu berhenti
        if ($this->option('list')) {
            $this->info('Daftar sheet dalam file:');
            foreach ($daftarSheet as $nama) {
                $this->line('  - '.$nama);
            }

            return self::SUCCESS;
        }

        $sheet = $this->option('sheet') ?: $daftarSheet[0];

        if (! in_array($sheet, $daftarSheet, true)) {
            $this->error("Sheet \"{$sheet}\" tidak ditemukan.");
            $this->line('Sheet yang tersedia: '.implode(', ', $daftarSheet));

            return self::FAILURE;
        }

        $batch = $this->option('batch') ?: 'Tanpa Batch';
        $tahun = $this->option('tahun') ? (int) $this->option('tahun') : null;

        $this->info("Membaca sheet \"{$sheet}\" ...");

        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly([$sheet]);
        $spreadsheet = $reader->load($file);

        // formatData = true agar tanggal terbaca sebagai teks yang bisa diparse
        $baris = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        $this->info('Jumlah baris terbaca (termasuk header): '.count($baris));

        try {
            $hasil = $importer->jalankan($baris, $batch, $tahun, basename($file));
        } catch (\Throwable $e) {
            $this->error('Import gagal: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->newLine();
        $this->info("Berhasil : {$hasil['berhasil']} startup");

        if ($hasil['gagal'] > 0) {
            $this->warn("Gagal    : {$hasil['gagal']} baris");
            $this->newLine();
            $this->line('Detail baris yang gagal:');
            foreach (array_slice($hasil['catatan'], 0, 20) as $catatan) {
                $this->line('  - '.$catatan);
            }
        }

        return self::SUCCESS;
    }
}
