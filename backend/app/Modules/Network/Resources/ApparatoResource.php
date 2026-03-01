<?php

namespace App\Modules\Network\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Topology\Resources\SitoResource;

class ApparatoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sito_id' => $this->sito_id,
            'sito' => new SitoResource($this->whenLoaded('sito')),
            'tipo' => $this->tipo,
            'marca' => $this->marca,
            'modello' => $this->modello,
            'ip_address' => $this->ip_address,
            'mac_address' => $this->mac_address,
            'subnet' => $this->subnet,
            'seriale' => $this->seriale,
            'stato' => $this->stato,
            'data_installazione' => $this->data_installazione,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
