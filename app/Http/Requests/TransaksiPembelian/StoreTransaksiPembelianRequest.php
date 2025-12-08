<?php

namespace App\Http\Requests\TransaksiPembelian;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransaksiPembelianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id' => ['required', 'exists:factories,id'],
            'tanggal_transaksi' => ['required', 'date'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.paket_id' => ['required', 'exists:pakets,id'],
            'details.*.qty' => ['required', 'integer', 'min:1'],
            'details.*.harga_per_unit' => ['required', 'numeric', 'min:0'],
            'details.*.total_bayar' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'details.required' => 'Minimal 1 item paket harus diisi.',
            'details.*.paket_id.required' => 'Paket harus dipilih.',
            'details.*.qty.required' => 'Jumlah harus diisi.',
            'details.*.harga_per_unit.required' => 'Harga per unit harus diisi.',
        ];
    }
}
