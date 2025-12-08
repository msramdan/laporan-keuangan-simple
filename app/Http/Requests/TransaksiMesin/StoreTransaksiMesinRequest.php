<?php

namespace App\Http\Requests\TransaksiMesin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransaksiMesinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'mesin_id' => ['required', 'exists:mesins,id'],
            'tanggal_transaksi' => ['required', 'date'],
            'nama_produk' => ['required', 'string', 'max:255'],
            'banyak_tsg' => ['required', 'numeric', 'min:0'],
            'banyak_tsg_tertolak' => ['nullable', 'numeric', 'min:0'],
            'harga_pabrik' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'status_lunas' => ['nullable', 'boolean'],
        ];
    }
}
