<?php

namespace App\Modules\Network\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApparatoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'operatore']);
    }

    public function rules(): array
    {
        return [
            'sito_id' => ['sometimes', 'required', 'exists:siti,id'],
            'tipo' => ['sometimes', 'required', 'in:switch,router,ap,telecamera,nvr,fibra,altro'],
            'marca' => ['nullable', 'string', 'max:255'],
            'modello' => ['nullable', 'string', 'max:255'],
            'ip_address' => ['nullable', 'ip'],
            'mac_address' => ['nullable', 'string', 'max:255'],
            'subnet' => ['nullable', 'string', 'max:255'],
            'seriale' => ['nullable', 'string', 'max:255'],
            'stato' => ['sometimes', 'required', 'in:attivo,guasto,manutenzione'],
            'data_installazione' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
        ];
    }
}
