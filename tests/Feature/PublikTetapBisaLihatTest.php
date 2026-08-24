<?php

namespace Tests\Feature;

use App\Models\Startup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regresi: memasang login untuk admin tidak boleh ikut mengunci
 * halaman-halaman yang memang sengaja dibuat terbuka untuk publik.
 */
class PublikTetapBisaLihatTest extends TestCase
{
    use RefreshDatabase;

    public function test_beranda_bisa_diakses_tanpa_login(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_statistik_bisa_diakses_tanpa_login(): void
    {
        $this->get('/statistik')->assertOk();
    }

    public function test_daftar_startup_bisa_diakses_tanpa_login(): void
    {
        $this->get('/startup')->assertOk();
    }

    public function test_detail_startup_bisa_diakses_tanpa_login(): void
    {
        $startup = Startup::factory()->create();

        $this->get("/startup/{$startup->id}")->assertOk();
    }
}
