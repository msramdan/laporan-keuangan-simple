<?php

namespace App\Http\Requests\Paket;

use Illuminate\Foundation\Http\FormRequest;

class StorePaketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:pakets,code'],
            'name' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
