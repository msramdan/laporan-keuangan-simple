<?php

namespace App\Http\Requests\Paket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('pakets', 'code')->ignore($this->paket)],
            'name' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
