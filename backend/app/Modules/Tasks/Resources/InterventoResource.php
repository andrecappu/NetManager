<?php

namespace App\Modules\Tasks\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Enti\Resources\EnteResource;
use App\Modules\Topology\Resources\SitoResource;
use App\Modules\Network\Resources\ApparatoResource;
use App\Modules\Auth\Resources\UserResource;

class InterventoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titolo' => $this->titolo,
            'descrizione' => $this->descrizione,
            'ente_id' => $this->ente_id,
            'ente' => new EnteResource($this->whenLoaded('ente')),
            'sito_id' => $this->sito_id,
            'sito' => new SitoResource($this->whenLoaded('sito')),
            'apparato_id' => $this->apparato_id,
            'apparato' => new ApparatoResource($this->whenLoaded('apparato')),
            'stato' => $this->stato,
            'priorita' => $this->priorita,
            'assegnato_a' => $this->assegnato_a,
            'assegnato_a_user' => new UserResource($this->whenLoaded('assegnatoA')),
            'creato_da' => $this->creato_da,
            'creato_da_user' => new UserResource($this->whenLoaded('creatoDa')),
            'data_scadenza' => $this->data_scadenza,
            'checklist_items' => ChecklistItemResource::collection($this->whenLoaded('checklistItems')),
            'allegati' => AllegatoResource::collection($this->whenLoaded('allegati')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
