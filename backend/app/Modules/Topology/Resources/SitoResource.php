<?php

namespace App\Modules\Topology\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Enti\Resources\EnteResource;
use App\Modules\Network\Resources\ApparatoResource;

class SitoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'ente_id' => $this->ente_id,
            'ente' => new EnteResource($this->whenLoaded('ente')),
            'indirizzo' => $this->indirizzo,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'tipo' => $this->tipo,
            'note' => $this->note,
            'apparati' => ApparatoResource::collection($this->whenLoaded('apparati')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
