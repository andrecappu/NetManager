<?php

namespace Database\Factories\Modules\Topology\Models;

use App\Modules\Topology\Models\Sito;
use App\Modules\Enti\Models\Ente;
use Illuminate\Database\Eloquent\Factories\Factory;

class SitoFactory extends Factory
{
    protected $model = Sito::class;

    public function definition(): array
    {
        // Generiamo coordinate realistiche per l'Italia
        $lat = $this->faker->latitude(36.0, 47.0);
        $lng = $this->faker->longitude(6.0, 18.0);

        return [
            'nome' => 'Sito ' . $this->faker->city(),
            'ente_id' => Ente::factory(),
            'indirizzo' => $this->faker->address(),
            'lat' => $lat,
            'lng' => $lng,
            'tipo' => $this->faker->randomElement(['rack', 'armadio', 'edificio', 'impianto_vsrv']),
            'note' => $this->faker->sentence(),
        ];
    }
}
