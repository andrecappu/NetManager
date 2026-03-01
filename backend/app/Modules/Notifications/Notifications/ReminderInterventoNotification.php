<?php

namespace App\Modules\Notifications\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Modules\Tasks\Models\Intervento;

class ReminderInterventoNotification extends Notification implements ShouldQueue
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
                    ->subject('Promemoria Scadenza Intervento: ' . $this->intervento->titolo)
                    ->greeting('Ciao ' . $notifiable->nome . ',')
                    ->line('Ti ricordiamo che l\'intervento a te assegnato scade tra meno di un\'ora.')
                    ->line('Titolo: ' . $this->intervento->titolo)
                    ->line('Scadenza: ' . $this->intervento->data_scadenza->format('d/m/Y H:i'))
                    ->action('Vedi Intervento', url('/interventi/' . $this->intervento->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'intervento_id' => $this->intervento->id,
            'titolo' => $this->intervento->titolo,
            'messaggio' => 'L\'intervento scade tra meno di un\'ora.',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'intervento_id' => $this->intervento->id,
            'titolo' => $this->intervento->titolo,
            'messaggio' => 'L\'intervento scade tra meno di un\'ora.',
        ]);
    }
}
