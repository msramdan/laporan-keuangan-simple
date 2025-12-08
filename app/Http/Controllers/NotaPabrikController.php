<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMesin;
use App\Models\NotaHistory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class NotaPabrikController extends Controller
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

        $clients = \App\Models\Client::orderBy('name')->get();
        $mesins = \App\Models\Mesin::orderBy('name')->get();

        return view('nota-pabrik.index', compact('transaksis', 'clients', 'mesins'));
    }

    public function print(Request $request)
    {
        $request->validate([
            'selected' => 'required|array|min:1',
            'selected.*' => 'exists:transaksi_mesins,id',
        ]);

        $transaksis = TransaksiMesin::with(['client', 'mesin'])
            ->whereIn('id', $request->selected)
            ->orderBy('tanggal_transaksi')
            ->get();

        // Get client name for header
        $clientName = $transaksis->first()?->client?->name ?? 'Perusahaan';
        
        // Build periode string
        $periode = '';
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $periode = date('d/m/Y', strtotime($request->start_date)) . ' - ' . date('d/m/Y', strtotime($request->end_date));
        } elseif ($request->filled('start_date')) {
            $periode = 'Dari ' . date('d/m/Y', strtotime($request->start_date));
        } elseif ($request->filled('end_date')) {
            $periode = 'Sampai ' . date('d/m/Y', strtotime($request->end_date));
        }

        // Save to history
        NotaHistory::create([
            'nota_type' => 'machine_factory',
            'parameters' => [
                'selected_ids' => $request->selected,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ],
            'created_by' => auth()->id(),
        ]);

        $pdf = Pdf::loadView('nota-pabrik.print', compact('transaksis', 'clientName', 'periode'));
        return $pdf->download('nota-pabrik-' . now()->format('Y-m-d') . '.pdf');
    }
}
