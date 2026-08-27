<?php

namespace Tests\Feature\Admin;

use App\Models\Startup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StartupCrudTest extends TestCase
{
    use RefreshDatabase;

    private function dataMinimal(array $override = []): array
    {
        return array_merge([
            'nama_startup' => 'Startup Uji',
            'nama_ceo' => 'CEO Uji',
            'jenis_kelamin_ceo' => 'L',
            'asal_invensi' => 'Mandiri',
            'status' => 'aktif',
        ], $override);
    }

    public function test_pengguna_login_bisa_membuat_startup_baru(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/startup', $this->dataMinimal());

        $this->assertDatabaseHas('startups', ['nama_startup' => 'Startup Uji']);
        $startup = Startup::where('nama_startup', 'Startup Uji')->firstOrFail();
        $response->assertRedirect(route('admin.startup.edit', $startup));
    }

    public function test_modal_awal_berformat_bebas_dikonversi_menjadi_angka(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/startup', $this->dataMinimal([
            'modal_awal' => 'Rp 150.000.000',
        ]));

        $startup = Startup::where('nama_startup', 'Startup Uji')->firstOrFail();

        $this->assertEquals(150_000_000, (float) $startup->modal_awal);
        $this->assertEquals('Rp 150.000.000', $startup->modal_awal_teks);
    }

    public function test_pengguna_login_bisa_mengubah_startup(): void
    {
        $user = User::factory()->create();
        $startup = Startup::factory()->create(['nama_startup' => 'Nama Lama']);

        $response = $this->actingAs($user)->put("/admin/startup/{$startup->id}", $this->dataMinimal([
            'nama_startup' => 'Nama Baru',
        ]));

        $response->assertRedirect();
        $this->assertEquals('Nama Baru', $startup->fresh()->nama_startup);
    }

    public function test_menghapus_startup_ikut_menghapus_data_anaknya(): void
    {
        $user = User::factory()->create();
        $startup = Startup::factory()->create();
        $startup->anggotaTim()->create(['nama' => 'Anggota Uji']);
        $startup->targetOutput()->create(['nama_target' => 'Target Uji', 'status' => 'proses']);

        $response = $this->actingAs($user)->delete("/admin/startup/{$startup->id}");

        $response->assertRedirect(route('admin.startup.index'));
        $this->assertDatabaseMissing('startups', ['id' => $startup->id]);
        $this->assertDatabaseCount('anggota_tim', 0);
        $this->assertDatabaseCount('target_output', 0);
    }

    public function test_tamu_tidak_bisa_membuat_startup(): void
    {
        $response = $this->post('/admin/startup', $this->dataMinimal());

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('startups', 0);
    }
}
