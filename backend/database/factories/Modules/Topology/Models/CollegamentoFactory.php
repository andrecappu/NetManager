<?php

namespace Database\Factories\Modules\Topology\Models;

use App\Modules\Topology\Models\Collegamento;
use App\Modules\Topology\Models\Sito;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollegamentoFactory extends Factory
{
    protected $model = Collegamento::class;

    public function definition(): array
    {
        return [
            'sito_origine_id' => Sito::factory(),
            'sito_destinazione_id' => Sito::factory(),
            'tipo' => $this->faker->randomElement(['fibra', 'wireless', 'rame']),
            'banda_mbps' => $this->faker->randomElement([100, 1000, 10000]),
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
