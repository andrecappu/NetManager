<?php

namespace App\Modules\Tasks\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Tasks\Models\Intervento;
use App\Modules\Notifications\Notifications\ReminderInterventoNotification;
use Carbon\Carbon;

class ReminderInterventoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $now = Carbon::now();
        $oneHourFromNow = $now->copy()->addHour();

        $interventi = Intervento::where('stato', '!=', 'completato')
            ->where('stato', '!=', 'annullato')
            ->whereNotNull('assegnato_a')
            ->whereNotNull('data_scadenza')
            ->whereBetween('data_scadenza', [$now, $oneHourFromNow])
            ->get();

        foreach ($interventi as $intervento) {
            if ($intervento->assegnatoA) {
                $intervento->assegnatoA->notify(new ReminderInterventoNotification($intervento));
            }
        }
    }
}
