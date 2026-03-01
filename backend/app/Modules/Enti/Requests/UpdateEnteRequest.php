<?php

namespace App\Modules\Enti\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'nome' => ['sometimes', 'required', 'string', 'max:255'],
            'tipo' => ['sometimes', 'required', 'in:comune,provincia,altro'],
            'codice_istat' => ['nullable', 'string', 'max:255'],
            'indirizzo' => ['nullable', 'string', 'max:255'],
            'referente' => ['nullable', 'string', 'max:255'],
            'contatto' => ['nullable', 'string', 'max:255'],
        ];
    }
}
