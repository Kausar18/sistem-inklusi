<?php

namespace Tests\Feature\Admin;

use App\Models\BidangUsaha;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BidangUsahaCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengguna_login_bisa_menambah_bidang_usaha(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/bidang-usaha', ['nama_bidang' => 'Agribisnis Digital']);

        $this->assertDatabaseHas('bidang_usaha', ['nama_bidang' => 'Agribisnis Digital']);
    }

    public function test_pengguna_login_bisa_mengubah_bidang_usaha(): void
    {
        $user = User::factory()->create();
        $bidang = BidangUsaha::create(['nama_bidang' => 'Nama Lama']);

        $response = $this->actingAs($user)->put("/admin/bidang-usaha/{$bidang->id}", ['nama_bidang' => 'Nama Baru']);

        $response->assertRedirect();
        $this->assertEquals('Nama Baru', $bidang->fresh()->nama_bidang);
    }

    public function test_pengguna_login_bisa_menghapus_bidang_usaha_yang_belum_dipakai(): void
    {
        $user = User::factory()->create();
        $bidang = BidangUsaha::create(['nama_bidang' => 'Akan Dihapus']);

        $response = $this->actingAs($user)->delete("/admin/bidang-usaha/{$bidang->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('bidang_usaha', ['id' => $bidang->id]);
    }

    public function test_bidang_usaha_yang_masih_dipakai_startup_tidak_bisa_dihapus(): void
    {
        $user = User::factory()->create();
        $bidang = BidangUsaha::create(['nama_bidang' => 'Masih Dipakai']);
        \App\Models\Startup::factory()->create(['bidang_usaha_id' => $bidang->id]);

        $this->actingAs($user)->delete("/admin/bidang-usaha/{$bidang->id}");

        $this->assertDatabaseHas('bidang_usaha', ['id' => $bidang->id]);
    }
}
