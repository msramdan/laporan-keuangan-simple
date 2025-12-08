<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMesin;
use Illuminate\Http\Request;

class RingkasanMesinController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiMesin::with(['client', 'mesin']);

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->end_date);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('mesin_id')) {
            $query->where('mesin_id', $request->mesin_id);
        }

        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->get();

        $summary = [
            'total_transaksi' => $transaksis->count(),
            'total_harga_pabrik' => $transaksis->sum('total_harga_pabrik'),
            'total_harga_jual' => $transaksis->sum('total_harga_jual'),
            'total_profit' => $transaksis->sum('total_harga_jual') - $transaksis->sum('total_harga_pabrik'),
            'total_lunas' => $transaksis->where('status_lunas', true)->count(),
        ];

        $clients = \App\Models\Client::orderBy('name')->get();
        $mesins = \App\Models\Mesin::orderBy('name')->get();

        return view('ringkasan-mesin.index', compact('transaksis', 'summary', 'clients', 'mesins'));
    }
}
