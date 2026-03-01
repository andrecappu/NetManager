<?php

namespace App\Modules\Network\Observers;

use App\Modules\Network\Models\Apparato;
use Illuminate\Support\Facades\Log;

class LogApparatoStatoObserver
{
    public function updated(Apparato $apparato): void
    {
        if ($apparato->wasChanged('stato')) {
            $oldStato = $apparato->getOriginal('stato');
            $newStato = $apparato->stato;
            
            Log::info("Apparato ID {$apparato->id} ({$apparato->marca} {$apparato->modello}) ha cambiato stato da {$oldStato} a {$newStato}");
            
            // Qui si potrebbe inserire in una tabella di log specifica nel DB
        }
    }
}
