<?php

namespace App\Modules\Calendar\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Auth\Resources\UserResource;
use App\Modules\Tasks\Resources\InterventoResource;

class CalendarioEventoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'intervento_id' => $this->intervento_id,
            'intervento' => new InterventoResource($this->whenLoaded('intervento')),
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'titolo' => $this->titolo,
            'data_inizio' => $this->data_inizio,
            'data_fine' => $this->data_fine,
            'colore' => $this->colore,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
