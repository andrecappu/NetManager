<?php

namespace App\Modules\Notifications\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Modules\Tasks\Models\Intervento;

class NuovoInterventoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $intervento;

    public function __construct(Intervento $intervento)
    {
        $this->intervento = $intervento;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Nuovo Intervento Assegnato: ' . $this->intervento->titolo)
                    ->greeting('Ciao ' . $notifiable->nome . ',')
                    ->line('Ti è stato assegnato un nuovo intervento.')
                    ->line('Titolo: ' . $this->intervento->titolo)
                    ->line('Priorità: ' . $this->intervento->priorita)
                    ->line('Scadenza: ' . ($this->intervento->data_scadenza ? $this->intervento->data_scadenza->format('d/m/Y H:i') : 'N/A'))
                    ->action('Vedi Intervento', url('/interventi/' . $this->intervento->id))
                    ->line('Grazie per il tuo lavoro!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'intervento_id' => $this->intervento->id,
            'titolo' => $this->intervento->titolo,
            'messaggio' => 'Ti è stato assegnato un nuovo intervento.',
            'priorita' => $this->intervento->priorita,
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'intervento_id' => $this->intervento->id,
            'titolo' => $this->intervento->titolo,
            'messaggio' => 'Ti è stato assegnato un nuovo intervento.',
            'priorita' => $this->intervento->priorita,
        ]);
    }
}
