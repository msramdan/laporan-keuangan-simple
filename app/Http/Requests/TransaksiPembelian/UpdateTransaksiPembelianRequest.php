<?php

namespace App\Http\Requests\TransaksiPembelian;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransaksiPembelianRequest extends FormRequest
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
            'details.*.id' => ['nullable', 'exists:transaksi_pembelian_details,id'],
            'details.*.paket_id' => ['required', 'exists:pakets,id'],
            'details.*.qty' => ['required', 'integer', 'min:1'],
            'details.*.harga_per_unit' => ['required', 'numeric', 'min:0'],
            'details.*.total_bayar' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
