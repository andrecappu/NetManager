<?php

namespace App\Modules\Tasks\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Auth\Resources\UserResource;

class ChecklistItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'intervento_id' => $this->intervento_id,
            'descrizione' => $this->descrizione,
            'completato' => $this->completato,
            'completato_at' => $this->completato_at,
            'completato_da' => $this->completato_da,
            'completato_da_user' => new UserResource($this->whenLoaded('completatoDa')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
