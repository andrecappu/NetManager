<?php

namespace App\Modules\Topology\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCollegamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'operatore']);
    }

    public function rules(): array
    {
        return [
            'sito_origine_id' => ['sometimes', 'required', 'exists:siti,id'],
            'sito_destinazione_id' => ['sometimes', 'required', 'exists:siti,id', 'different:sito_origine_id'],
            'tipo' => ['sometimes', 'required', 'in:fibra,wireless,rame'],
            'banda_mbps' => ['nullable', 'integer', 'min:1'],
            'note' => ['nullable', 'string'],
        ];
    }
}
