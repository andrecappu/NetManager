<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Auth\Models\User;
use App\Modules\Enti\Models\Ente;
use App\Modules\Topology\Models\Sito;
use App\Modules\Network\Models\Apparato;
use App\Modules\Topology\Models\Collegamento;
use App\Modules\Tasks\Models\Intervento;
use App\Modules\Tasks\Models\ChecklistItem;
use App\Modules\Calendar\Models\CalendarioEvento;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Creazione Ruoli
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleOperatore = Role::create(['name' => 'operatore']);
        $roleViewer = Role::create(['name' => 'viewer']);

        // 2. Creazione Utenti di test
        $admin = User::create([
            'nome' => 'Admin',
            'cognome' => 'Sistema',
            'email' => 'admin@netmanager.it',
            'password' => Hash::make('password'),
            'attivo' => true,
        ]);
        $admin->assignRole($roleAdmin);

        $operatore1 = User::create([
            'nome' => 'Mario',
            'cognome' => 'Rossi',
            'email' => 'mario.rossi@netmanager.it',
            'password' => Hash::make('password'),
            'attivo' => true,
        ]);
        $operatore1->assignRole($roleOperatore);

        $operatore2 = User::create([
            'nome' => 'Luigi',
            'cognome' => 'Verdi',
            'email' => 'luigi.verdi@netmanager.it',
            'password' => Hash::make('password'),
            'attivo' => true,
        ]);
        $operatore2->assignRole($roleOperatore);

        // 3. Creazione Enti
        $enti = Ente::factory()->count(3)->create();

        // Associa operatori agli enti
        $operatore1->enti()->attach($enti->pluck('id')->toArray());
        $operatore2->enti()->attach([$enti->first()->id]);

        // 4. Creazione Siti e Apparati
        $siti = collect();
        foreach ($enti as $ente) {
            $sitiEnte = Sito::factory()->count(rand(3, 5))->create(['ente_id' => $ente->id]);
            $siti = $siti->merge($sitiEnte);
            
            foreach ($sitiEnte as $sito) {
                Apparato::factory()->count(rand(2, 6))->create([
                    'sito_id' => $sito->id,
                ]);
            }
        }

        // 5. Creazione Collegamenti tra siti
        for ($i = 0; $i < 15; $i++) {
            $sitoOrigine = $siti->random();
            $sitoDestinazione = $siti->where('id', '!=', $sitoOrigine->id)->random();
            
            Collegamento::factory()->create([
                'sito_origine_id' => $sitoOrigine->id,
                'sito_destinazione_id' => $sitoDestinazione->id,
            ]);
        }

        // 6. Creazione Interventi
        $operatori = [$operatore1, $operatore2];
        
        for ($i = 0; $i < 20; $i++) {
            $sito = $siti->random();
            $apparato = Apparato::where('sito_id', $sito->id)->inRandomOrder()->first();
            $operatore = $operatori[array_rand($operatori)];
            
            $intervento = Intervento::factory()->create([
                'ente_id' => $sito->ente_id,
                'sito_id' => $sito->id,
                'apparato_id' => $apparato ? $apparato->id : null,
                'assegnato_a' => $operatore->id,
                'creato_da' => $admin->id,
            ]);

            // 7. Creazione Checklist per l'intervento
            $tasks = ['Verifica alimentazione', 'Controllo cavi di rete', 'Aggiornamento firmware', 'Test di connettività', 'Pulizia apparato'];
            $numTasks = rand(2, 4);
            $selectedTasks = array_rand(array_flip($tasks), $numTasks);
            
            if (!is_array($selectedTasks)) {
                $selectedTasks = [$selectedTasks];
            }

            foreach ($selectedTasks as $taskDesc) {
                $completato = $intervento->stato === 'completato' || ($intervento->stato === 'in_corso' && rand(0, 1));
                
                ChecklistItem::create([
                    'intervento_id' => $intervento->id,
                    'descrizione' => $taskDesc,
                    'completato' => $completato,
                    'completato_at' => $completato ? Carbon::now()->subHours(rand(1, 48)) : null,
                    'completato_da' => $completato ? $operatore->id : null,
                ]);
            }

            // 8. Creazione Evento a Calendario se l'intervento non è completato o annullato
            if (in_array($intervento->stato, ['todo', 'in_corso']) && $intervento->data_scadenza) {
                $dataInizio = Carbon::parse($intervento->data_scadenza)->subHours(2);
                
                CalendarioEvento::create([
                    'intervento_id' => $intervento->id,
                    'user_id' => $operatore->id,
                    'titolo' => 'INT-' . $intervento->id . ': ' . $intervento->titolo,
                    'data_inizio' => $dataInizio,
                    'data_fine' => $intervento->data_scadenza,
                    'colore' => $operatore->id === $operatore1->id ? '#3b82f6' : '#10b981', // Blu per op1, Verde per op2
                ]);
            }
        }
    }
}
