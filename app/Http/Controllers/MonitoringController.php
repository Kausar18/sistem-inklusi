<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use App\Models\Startup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    /**
     * Simpan satu catatan pemantauan kinerja.
     *
     * Catatan terbaru otomatis menjadi titik "sesudah" pada
     * perbandingan kinerja di halaman detail.
     */
    public function store(Request $request, Startup $startup): RedirectResponse
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'periode' => ['nullable', 'string', 'max:50'],
            'omzet' => ['nullable', 'string', 'max:30'],
            'tenaga_kerja_l' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'tenaga_kerja_p' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'jumlah_mitra' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'wilayah_penjualan' => ['nullable', 'string', 'max:255'],
            'izin_edar' => ['nullable', 'string', 'max:255'],
            'catatan' => ['nullable', 'string', 'max:5000'],
        ], [], [
            'tanggal' => 'tanggal pencatatan',
            'omzet' => 'omzet',
            'tenaga_kerja_l' => 'tenaga kerja laki-laki',
            'tenaga_kerja_p' => 'tenaga kerja perempuan',
            'jumlah_mitra' => 'jumlah mitra',
            'wilayah_penjualan' => 'wilayah penjualan',
        ]);

        // Omzet boleh ditulis "1.200.000.000" atau "1200000000"
        $data['omzet'] = $this->keAngka($data['omzet'] ?? null);

        $startup->monitoring()->create($data);

        return back()
            ->with('sukses', 'Data pemantauan tersimpan.')
            ->withFragment('kinerja');
    }

    /** Hapus satu catatan pemantauan. */
    public function destroy(Startup $startup, Monitoring $monitoring): RedirectResponse
    {
        abort_unless($monitoring->startup_id === $startup->id, 404);

        $monitoring->delete();

        return back()
            ->with('sukses', 'Catatan pemantauan dihapus.')
            ->withFragment('kinerja');
    }

    private function keAngka(?string $nilai): ?float
    {
        if (blank($nilai)) {
            return null;
        }

        $bersih = preg_replace('/[^\d]/', '', $nilai);

        return $bersih === '' ? null : (float) $bersih;
    }
}
