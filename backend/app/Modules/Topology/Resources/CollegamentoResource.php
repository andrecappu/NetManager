<?php

namespace App\Modules\Topology\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollegamentoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sito_origine_id' => $this->sito_origine_id,
            'sito_destinazione_id' => $this->sito_destinazione_id,
            'sito_origine' => new SitoResource($this->whenLoaded('sitoOrigine')),
            'sito_destinazione' => new SitoResource($this->whenLoaded('sitoDestinazione')),
            'tipo' => $this->tipo,
            'banda_mbps' => $this->banda_mbps,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
