<?php

namespace Database\Factories\Modules\Tasks\Models;

use App\Modules\Tasks\Models\Intervento;
use App\Modules\Enti\Models\Ente;
use App\Modules\Topology\Models\Sito;
use App\Modules\Network\Models\Apparato;
use App\Modules\Auth\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterventoFactory extends Factory
{
    protected $model = Intervento::class;

    public function definition(): array
    {
        return [
            'titolo' => $this->faker->sentence(4),
            'descrizione' => $this->faker->paragraph(),
            'ente_id' => Ente::factory(),
            'sito_id' => Sito::factory(),
            'apparato_id' => Apparato::factory(),
            'stato' => $this->faker->randomElement(['todo', 'in_corso', 'completato', 'annullato']),
            'priorita' => $this->faker->randomElement(['bassa', 'media', 'alta', 'urgente']),
            'assegnato_a' => User::factory(),
            'creato_da' => User::factory(),
            'data_scadenza' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
