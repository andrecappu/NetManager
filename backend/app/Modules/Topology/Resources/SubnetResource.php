<?php

namespace App\Modules\Topology\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubnetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sito_id' => $this->sito_id,
            'sito' => new SitoResource($this->whenLoaded('sito')),
            'cidr' => $this->cidr,
            'gateway' => $this->gateway,
            'vlan_id' => $this->vlan_id,
            'descrizione' => $this->descrizione,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
