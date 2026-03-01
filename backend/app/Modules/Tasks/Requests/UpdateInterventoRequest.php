<?php

namespace App\Modules\Tasks\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInterventoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'operatore']);
    }

    public function rules(): array
    {
        return [
            'titolo' => ['sometimes', 'required', 'string', 'max:255'],
            'descrizione' => ['nullable', 'string'],
            'ente_id' => ['sometimes', 'required', 'exists:enti,id'],
            'sito_id' => ['nullable', 'exists:siti,id'],
            'apparato_id' => ['nullable', 'exists:apparati,id'],
            'stato' => ['sometimes', 'required', 'in:todo,in_corso,completato,annullato'],
            'priorita' => ['sometimes', 'required', 'in:bassa,media,alta,urgente'],
            'assegnato_a' => ['nullable', 'exists:users,id'],
            'data_scadenza' => ['nullable', 'date'],
        ];
    }
}
