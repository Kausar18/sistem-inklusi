<?php

namespace Database\Factories;

use App\Models\Startup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Startup>
 */
class StartupFactory extends Factory
{
    protected $model = Startup::class;

    public function definition(): array
    {
        return [
            'nama_startup'      => fake()->unique()->company(),
            'nama_ceo'          => fake()->name(),
            'jenis_kelamin_ceo' => fake()->randomElement(['L', 'P']),
            'asal_invensi'      => 'Mandiri',
            'status'            => 'aktif',
            'tenaga_kerja_l'    => fake()->numberBetween(0, 10),
            'tenaga_kerja_p'    => fake()->numberBetween(0, 10),
            'omset_awal'        => fake()->numberBetween(0, 500_000_000),
            'kota'              => fake()->city(),
        ];
    }
}
