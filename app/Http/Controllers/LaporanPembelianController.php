<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPembelian;
use App\Models\NotaHistory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiPembelian::with(['factory', 'details.paket']);

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->end_date);
        }

        // Filter by factory
        if ($request->filled('factory_id')) {
            $query->where('factory_id', $request->factory_id);
        }

        // Filter by status
        if ($request->filled('status_lunas')) {
            $query->where('status_lunas', $request->status_lunas === 'lunas');
        }

        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->get();

        // Summary calculations
        $summary = [
            'total_transaksi' => $transaksis->count(),
            'total_paket' => $transaksis->sum(fn($t) => $t->details->sum('qty')),
            'total_harga' => $transaksis->sum('grand_total'),
            'total_bayar' => $transaksis->sum(fn($t) => $t->details->sum('total_bayar')),
            'total_hutang' => $transaksis->sum(fn($t) => $t->details->sum('total') - $t->details->sum('total_bayar')),
            'total_lunas' => $transaksis->where('status_lunas', true)->count(),
        ];

        $factories = \App\Models\Factory::orderBy('name')->get();

        return view('laporan-pembelian.index', compact('transaksis', 'summary', 'factories'));
    }

    public function print(Request $request)
    {
        $query = TransaksiPembelian::with(['factory', 'details.paket']);

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->end_date);
        }
        if ($request->filled('factory_id')) {
            $query->where('factory_id', $request->factory_id);
        }
        if ($request->filled('status_lunas')) {
            $query->where('status_lunas', $request->status_lunas === 'lunas');
        }

        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->get();

        // Save to history
        NotaHistory::create([
            'nota_type' => 'purchase',
            'parameters' => $request->only(['start_date', 'end_date', 'factory_id', 'status_lunas']),
            'created_by' => auth()->id(),
        ]);

        $pdf = Pdf::loadView('laporan-pembelian.print', compact('transaksis'));
        return $pdf->download('laporan-pembelian-' . now()->format('Y-m-d') . '.pdf');
    }
}
