<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaksiPembelian\StoreTransaksiPembelianRequest;
use App\Http\Requests\TransaksiPembelian\UpdateTransaksiPembelianRequest;
use App\Models\Factory;
use App\Models\Paket;
use App\Models\TransaksiPembelian;
use App\Models\TransaksiPembelianDetail;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransaksiPembelianController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $transaksis = TransaksiPembelian::with('factory')
                ->withCount('details')
                ->withSum('details', 'total')
                ->withSum('details', 'total_bayar');

            return DataTables::of($transaksis)
                ->addIndexColumn()
                ->editColumn('tanggal_transaksi', fn($row) => $row->tanggal_transaksi->format('Y-m-d'))
                ->addColumn('factory_name', fn($row) => $row->factory->name ?? '-')
                ->addColumn('total_items', fn($row) => $row->details_count)
                ->editColumn('grand_total', fn($row) => number_format($row->grand_total, 0, ',', '.'))
                ->addColumn('total_bayar', fn($row) => number_format($row->details_sum_total_bayar ?? 0, 0, ',', '.'))
                ->addColumn('total_hutang', fn($row) => number_format(($row->grand_total - ($row->details_sum_total_bayar ?? 0)), 0, ',', '.'))
                ->editColumn('status_lunas', fn($row) => $row->status_lunas 
                    ? '<span class="badge bg-success">Lunas</span>' 
                    : '<span class="badge bg-warning">Belum Lunas</span>')
                ->addColumn('action', 'transaksi-pembelians.include.action')
                ->rawColumns(['status_lunas', 'action'])
                ->toJson();
        }

        return view('transaksi-pembelians.index');
    }

    public function create()
    {
        $factories = Factory::orderBy('name')->get();
        $pakets = Paket::orderBy('name')->get();
        
        return view('transaksi-pembelians.create', compact('factories', 'pakets'));
    }

    public function store(StoreTransaksiPembelianRequest $request)
    {
        DB::transaction(function () use ($request) {
            $transaksi = TransaksiPembelian::create([
                'factory_id' => $request->factory_id,
                'tanggal_transaksi' => $request->tanggal_transaksi,
            ]);

            foreach ($request->details as $detail) {
                $transaksi->details()->create([
                    'paket_id' => $detail['paket_id'],
                    'qty' => $detail['qty'],
                    'harga_per_unit' => $detail['harga_per_unit'],
                    'total_bayar' => $detail['total_bayar'] ?? 0,
                ]);
            }
        });

        return redirect()->route('transaksi-pembelians.index')
            ->with('success', 'Transaksi pembelian berhasil ditambahkan.');
    }

    public function show(TransaksiPembelian $transaksiPembelian)
    {
        $transaksiPembelian->load(['factory', 'details.paket']);
        return view('transaksi-pembelians.show', compact('transaksiPembelian'));
    }

    public function edit(TransaksiPembelian $transaksiPembelian)
    {
        $transaksiPembelian->load('details.paket');
        $factories = Factory::orderBy('name')->get();
        $pakets = Paket::orderBy('name')->get();
        
        return view('transaksi-pembelians.edit', compact('transaksiPembelian', 'factories', 'pakets'));
    }

    public function update(UpdateTransaksiPembelianRequest $request, TransaksiPembelian $transaksiPembelian)
    {
        DB::transaction(function () use ($request, $transaksiPembelian) {
            $transaksiPembelian->update([
                'factory_id' => $request->factory_id,
                'tanggal_transaksi' => $request->tanggal_transaksi,
            ]);

            // Get existing detail IDs
            $existingIds = collect($request->details)
                ->pluck('id')
                ->filter()
                ->toArray();

            // Delete removed details
            $transaksiPembelian->details()
                ->whereNotIn('id', $existingIds)
                ->delete();

            // Update or create details
            foreach ($request->details as $detail) {
                if (!empty($detail['id'])) {
                    TransaksiPembelianDetail::find($detail['id'])->update([
                        'paket_id' => $detail['paket_id'],
                        'qty' => $detail['qty'],
                        'harga_per_unit' => $detail['harga_per_unit'],
                        'total_bayar' => $detail['total_bayar'] ?? 0,
                    ]);
                } else {
                    $transaksiPembelian->details()->create([
                        'paket_id' => $detail['paket_id'],
                        'qty' => $detail['qty'],
                        'harga_per_unit' => $detail['harga_per_unit'],
                        'total_bayar' => $detail['total_bayar'] ?? 0,
                    ]);
                }
            }
        });

        return redirect()->route('transaksi-pembelians.index')
            ->with('success', 'Transaksi pembelian berhasil diperbarui.');
    }

    public function destroy(TransaksiPembelian $transaksiPembelian)
    {
        $transaksiPembelian->delete();

        return redirect()->route('transaksi-pembelians.index')
            ->with('success', 'Transaksi pembelian berhasil dihapus.');
    }

    /**
     * Get transaction detail as JSON for modal
     */
    public function detail(TransaksiPembelian $transaksiPembelian)
    {
        $transaksiPembelian->load(['factory', 'details.paket']);
        
        $grandTotal = $transaksiPembelian->details->sum(fn($d) => $d->qty * $d->harga_per_unit);
        $totalBayar = $transaksiPembelian->details->sum('total_bayar');
        
        return response()->json([
            'id' => $transaksiPembelian->id,
            'factory' => $transaksiPembelian->factory,
            'tanggal_transaksi' => $transaksiPembelian->tanggal_transaksi->format('Y-m-d'),
            'status_lunas' => $transaksiPembelian->status_lunas,
            'details' => $transaksiPembelian->details,
            'grand_total' => $grandTotal,
            'total_bayar' => $totalBayar,
            'total_hutang' => $grandTotal - $totalBayar,
        ]);
    }

    /**
     * Process payment for transaction
     */
    public function payment(TransaksiPembelian $transaksiPembelian)
    {
        $transaksiPembelian->load('details');
        
        $fullPay = request()->boolean('full_pay');
        $amount = (float) request('amount', 0);
        
        $grandTotal = $transaksiPembelian->details->sum(fn($d) => $d->qty * $d->harga_per_unit);
        $currentPaid = $transaksiPembelian->details->sum('total_bayar');
        $remaining = $grandTotal - $currentPaid;
        
        if ($fullPay) {
            // Distribute remaining to all unpaid details
            $amountToDistribute = $remaining;
        } else {
            if ($amount <= 0) {
                return response()->json(['success' => false, 'message' => 'Jumlah pembayaran tidak valid.'], 400);
            }
            if ($amount > $remaining) {
                return response()->json(['success' => false, 'message' => 'Jumlah pembayaran melebihi sisa hutang.'], 400);
            }
            $amountToDistribute = $amount;
        }
        
        // Distribute payment to details
        foreach ($transaksiPembelian->details as $detail) {
            if ($amountToDistribute <= 0) break;
            
            $detailTotal = $detail->qty * $detail->harga_per_unit;
            $detailRemaining = $detailTotal - $detail->total_bayar;
            
            if ($detailRemaining > 0) {
                $payForThis = min($amountToDistribute, $detailRemaining);
                $detail->update(['total_bayar' => $detail->total_bayar + $payForThis]);
                $amountToDistribute -= $payForThis;
            }
        }
        
        // Check if fully paid and update status
        $newTotalPaid = $transaksiPembelian->fresh()->details->sum('total_bayar');
        if ($newTotalPaid >= $grandTotal) {
            $transaksiPembelian->update(['status_lunas' => true]);
        }
        
        return response()->json([
            'success' => true,
            'message' => $fullPay ? 'Transaksi berhasil dilunaskan.' : 'Pembayaran berhasil dicatat.',
        ]);
    }

    /**
     * Pay a specific item (detail) in the transaction
     */
    public function payItem(TransaksiPembelian $transaksiPembelian)
    {
        $detailId = (int) request('detail_id');
        $amount = (float) request('amount', 0);
        
        $detail = $transaksiPembelian->details()->find($detailId);
        
        if (!$detail) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan.'], 404);
        }
        
        $detailTotal = $detail->qty * $detail->harga_per_unit;
        $detailRemaining = $detailTotal - $detail->total_bayar;
        
        if ($amount <= 0) {
            return response()->json(['success' => false, 'message' => 'Jumlah pembayaran tidak valid.'], 400);
        }
        
        if ($amount > $detailRemaining) {
            return response()->json(['success' => false, 'message' => 'Jumlah melebihi sisa hutang item ini.'], 400);
        }
        
        $detail->update(['total_bayar' => $detail->total_bayar + $amount]);
        
        // Check if all items are fully paid and update transaction status
        $transaksiPembelian->load('details');
        $grandTotal = $transaksiPembelian->details->sum(fn($d) => $d->qty * $d->harga_per_unit);
        $totalPaid = $transaksiPembelian->details->sum('total_bayar');
        
        if ($totalPaid >= $grandTotal) {
            $transaksiPembelian->update(['status_lunas' => true]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Pembayaran item berhasil.',
        ]);
    }
}
