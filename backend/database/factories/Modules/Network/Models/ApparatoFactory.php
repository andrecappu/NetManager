<?php

namespace Database\Factories\Modules\Network\Models;

use App\Modules\Network\Models\Apparato;
use App\Modules\Topology\Models\Sito;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApparatoFactory extends Factory
{
    protected $model = Apparato::class;

    public function definition(): array
    {
        return [
            'sito_id' => Sito::factory(),
            'tipo' => $this->faker->randomElement(['switch', 'router', 'ap', 'telecamera', 'nvr', 'fibra', 'altro']),
            'marca' => $this->faker->randomElement(['Cisco', 'MikroTik', 'Ubiquiti', 'Dahua', 'Hikvision']),
            'modello' => $this->faker->bothify('MOD-####??'),
            'ip_address' => $this->faker->localIpv4(),
            'mac_address' => $this->faker->macAddress(),
            'subnet' => '255.255.255.0',
            'seriale' => $this->faker->uuid(),
            'stato' => $this->faker->randomElement(['attivo', 'attivo', 'attivo', 'guasto', 'manutenzione']),
            'data_installazione' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
