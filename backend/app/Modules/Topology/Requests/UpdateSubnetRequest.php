<?php

namespace App\Modules\Topology\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubnetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'operatore']);
    }

    public function rules(): array
    {
        return [
            'sito_id' => ['sometimes', 'required', 'exists:siti,id'],
            'cidr' => ['sometimes', 'required', 'string', 'max:255'],
            'gateway' => ['nullable', 'ip'],
            'vlan_id' => ['nullable', 'integer', 'min:1', 'max:4094'],
            'descrizione' => ['nullable', 'string', 'max:255'],
        ];
    }
}
