<?php

namespace App\Http\Requests\Mesin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMesinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('mesins', 'code')->ignore($this->mesin)],
            'name' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
