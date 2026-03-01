<?php

namespace App\Modules\Enti\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'tipo' => $this->tipo,
            'codice_istat' => $this->codice_istat,
            'indirizzo' => $this->indirizzo,
            'referente' => $this->referente,
            'contatto' => $this->contatto,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
