<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('clients', 'code')->ignore($this->client)],
            'name' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
