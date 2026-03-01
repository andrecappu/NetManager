<?php

namespace Database\Factories\Modules\Enti\Models;

use App\Modules\Enti\Models\Ente;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnteFactory extends Factory
{
    protected $model = Ente::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->company(),
            'tipo' => $this->faker->randomElement(['comune', 'provincia', 'altro']),
            'codice_istat' => $this->faker->numerify('######'),
            'indirizzo' => $this->faker->address(),
            'referente' => $this->faker->name(),
            'contatto' => $this->faker->phoneNumber(),
        ];
    }
}
