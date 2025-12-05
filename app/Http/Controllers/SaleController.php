<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sale\StoreSaleRequest;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $sales = Sale::query();

            return DataTables::of($sales)
                ->addIndexColumn()
                ->addColumn('action', 'sales.include.action')
                ->editColumn('grand_total', function ($row) {
                    return format_rupiah($row->grand_total);
                })
                ->editColumn('date', fn($row) => format_date($row->date, 'Y-m-d'))
                ->editColumn('created_at', fn($row) => format_date($row->created_at))
                ->editColumn('updated_at', fn($row) => format_date($row->updated_at))
                ->toJson();
        }

        return view('sales.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch printable items that haven't been sold yet (optional logic, but good for data integrity)
        // Or just fetch all printable items? Requirement: "Tampilkan semua data pada inputan Nota pabrik tapi hanya yg di centang cetak saja"
        // Let's assume we can sell items from stock.
        // I'll fetch PurchaseItems where is_printable = true.
        // To avoid selling the same item twice, I should check if it's already in sale_items.
        
        $items = PurchaseItem::where('is_printable', true)
            ->doesntHave('saleItem') // Ensure 1-to-1 sale
            ->with('product', 'purchase.factory')
            ->get();

        return view('sales.create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        DB::transaction(function () use ($request) {
            $grandTotal = 0;
            $totalReceivable = 0;

            foreach ($request->items as $item) {
                if (isset($item['purchase_item_id'])) {
                    $grandTotal += $item['selling_price'];
                    $totalReceivable += $item['receivable_amount'];
                }
            }

            $sale = Sale::create([
                'buyer_name' => $request->buyer_name,
                'date' => $request->date,
                'grand_total' => $grandTotal,
                'total_receivable' => $totalReceivable,
                'total_paid' => $grandTotal - $totalReceivable,
                'is_paid' => $totalReceivable == 0,
            ]);

            foreach ($request->items as $item) {
                if (isset($item['purchase_item_id'])) {
                    $sale->items()->create([
                        'purchase_item_id' => $item['purchase_item_id'],
                        'selling_price' => $item['selling_price'],
                        'receivable_amount' => $item['receivable_amount'],
                    ]);
                }
            }
        });

        return redirect()->route('sales.index')
            ->with('success', 'Sale created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load('items.purchaseItem.product', 'items.purchaseItem.purchase.factory');
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        // Editing sales might be complex if we want to add/remove items.
        // For now, let's just allow editing the header or deleting.
        // Or re-implement the create logic but with pre-selected items.
        // Given complexity, I'll skip full edit for now and focus on Create & Print.
        return redirect()->route('sales.index')->with('error', 'Edit feature not implemented yet.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function print(Sale $sale)
    {
        $sale->load('items.purchaseItem.product', 'items.purchaseItem.purchase.factory');
        $pdf = Pdf::loadView('sales.print', compact('sale'));
        return $pdf->stream('nota-pembeli-' . $sale->buyer_name . '-' . $sale->date->format('Y-m-d') . '.pdf');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')
            ->with('success', 'Sale deleted successfully.');
    }
}
