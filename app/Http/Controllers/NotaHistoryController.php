<?php

namespace App\Http\Controllers;

use App\Models\NotaHistory;
use Yajra\DataTables\Facades\DataTables;

class NotaHistoryController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $histories = NotaHistory::with('creator');

            return DataTables::of($histories)
                ->addIndexColumn()
                ->editColumn('created_at', fn($row) => $row->created_at->format('Y-m-d H:i'))
                ->addColumn('creator_name', fn($row) => $row->creator->name ?? '-')
                ->editColumn('nota_type', function($row) {
                    return match($row->nota_type) {
                        'purchase' => '<span class="badge bg-primary">Pembelian</span>',
                        'machine_factory' => '<span class="badge bg-info">Nota Pabrik</span>',
                        'machine_sales' => '<span class="badge bg-success">Nota Penjualan</span>',
                        default => $row->nota_type,
                    };
                })
                ->addColumn('action', 'nota-histories.include.action')
                ->rawColumns(['nota_type', 'action'])
                ->toJson();
        }

        return view('nota-histories.index');
    }

    public function show(NotaHistory $notaHistory)
    {
        return view('nota-histories.show', compact('notaHistory'));
    }

    public function regenerate(NotaHistory $notaHistory)
    {
        // Get parameters and regenerate the nota directly
        $parameters = $notaHistory->parameters ?? [];
        
        switch ($notaHistory->nota_type) {
            case 'purchase':
                // Regenerate purchase report
                $query = \App\Models\TransaksiPembelian::with(['factory', 'details.paket']);
                
                if (!empty($parameters['start_date'])) {
                    $query->whereDate('tanggal_transaksi', '>=', $parameters['start_date']);
                }
                if (!empty($parameters['end_date'])) {
                    $query->whereDate('tanggal_transaksi', '<=', $parameters['end_date']);
                }
                if (!empty($parameters['factory_id'])) {
                    $query->where('factory_id', $parameters['factory_id']);
                }
                
                $transaksis = $query->orderBy('tanggal_transaksi')->get();
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan-pembelian.print', compact('transaksis'));
                return $pdf->download('laporan-pembelian-' . now()->format('Y-m-d') . '.pdf');
                
            case 'machine_factory':
                // Regenerate factory nota
                $transaksis = \App\Models\TransaksiMesin::with(['client', 'mesin'])
                    ->whereIn('id', $parameters['selected_ids'] ?? [])
                    ->orderBy('tanggal_transaksi')
                    ->get();
                    
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('nota-pabrik.print', compact('transaksis'));
                return $pdf->download('nota-pabrik-' . now()->format('Y-m-d') . '.pdf');
                
            case 'machine_sales':
                // Regenerate sales nota
                $transaksis = \App\Models\TransaksiMesin::with(['client', 'mesin'])
                    ->whereIn('id', $parameters['selected_ids'] ?? [])
                    ->orderBy('tanggal_transaksi')
                    ->get();
                    
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('nota-penjualan.print', compact('transaksis'));
                return $pdf->download('nota-penjualan-' . now()->format('Y-m-d') . '.pdf');
                
            default:
                return back()->with('error', 'Tipe nota tidak dikenali.');
        }
    }

    public function destroy(NotaHistory $notaHistory)
    {
        $notaHistory->delete();

        return redirect()->route('nota-histories.index')
            ->with('success', 'Riwayat nota berhasil dihapus.');
    }
}
