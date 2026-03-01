<?php

namespace App\Modules\Topology\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSitoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'operatore']);
    }

    public function rules(): array
    {
        return [
            'nome' => ['sometimes', 'required', 'string', 'max:255'],
            'indirizzo' => ['nullable', 'string', 'max:255'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'tipo' => ['sometimes', 'required', 'in:rack,armadio,edificio,impianto_vsrv'],
            'note' => ['nullable', 'string'],
        ];
    }
}
