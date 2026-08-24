<?php

namespace Tests\Feature\Admin;

use App\Models\Startup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AksesAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_dialihkan_ke_login_saat_membuka_panel_admin(): void
    {
        $response = $this->get('/admin/startup');

        $response->assertRedirect('/login');
    }

    public function test_tamu_dialihkan_ke_login_saat_mencoba_menambah_pendampingan(): void
    {
        $startup = Startup::factory()->create();

        $response = $this->post("/startup/{$startup->id}/pendampingan", [
            'jenis'   => 'training',
            'tanggal' => now()->toDateString(),
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('pendampingan', 0);
    }

    public function test_tamu_dialihkan_ke_login_saat_mencoba_menambah_pemantauan(): void
    {
        $startup = Startup::factory()->create();

        $response = $this->post("/startup/{$startup->id}/monitoring", [
            'tanggal' => now()->toDateString(),
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('monitoring', 0);
    }

    public function test_pengguna_login_bisa_membuka_panel_admin(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/startup');

        $response->assertOk();
    }

    public function test_pengguna_login_bisa_menambah_pendampingan(): void
    {
        $user = User::factory()->create();
        $startup = Startup::factory()->create();

        $response = $this->actingAs($user)->post("/startup/{$startup->id}/pendampingan", [
            'jenis'   => 'training',
            'tanggal' => now()->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('pendampingan', 1);
    }
}
