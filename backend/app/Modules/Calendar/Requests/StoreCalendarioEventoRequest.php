<?php

namespace App\Modules\Calendar\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCalendarioEventoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'operatore']);
    }

    public function rules(): array
    {
        return [
            'intervento_id' => ['nullable', 'exists:interventi,id'],
            'user_id' => ['required', 'exists:users,id'],
            'titolo' => ['required', 'string', 'max:255'],
            'data_inizio' => ['required', 'date'],
            'data_fine' => ['required', 'date', 'after_or_equal:data_inizio'],
            'colore' => ['nullable', 'string', 'max:7'],
            'note' => ['nullable', 'string'],
        ];
    }
}
