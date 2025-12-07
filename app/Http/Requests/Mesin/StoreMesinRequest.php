<?php

namespace App\Http\Requests\Mesin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMesinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:mesins,code'],
            'name' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
