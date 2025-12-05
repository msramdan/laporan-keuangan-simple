<?php

namespace App\Http\Controllers;

use App\Http\Requests\Purchase\StorePurchaseRequest;
use App\Http\Requests\Purchase\UpdatePurchaseRequest;
use App\Models\Factory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $purchases = Purchase::with('factory')->select('purchases.*');

            return \Yajra\DataTables\Facades\DataTables::of($purchases)
                ->addIndexColumn()
                ->addColumn('action', 'purchases.include.action')
                ->addColumn('factory_name', function ($row) {
                    return $row->factory ? $row->factory->name : '-';
                })
                ->editColumn('date', fn($row) => format_date($row->date, 'Y-m-d'))
                ->editColumn('grand_total', function ($row) {
                    return format_rupiah($row->grand_total);
                })
                ->editColumn('total_debt', function ($row) {
                    return format_rupiah($row->total_debt);
                })
                ->editColumn('is_paid', function ($row) {
                    return $row->is_paid ? '<span class="badge bg-success">Paid</span>' : '<span class="badge bg-danger">Unpaid</span>';
                })
                ->editColumn('created_at', fn($row) => format_date($row->created_at))
                ->editColumn('updated_at', fn($row) => format_date($row->updated_at))
                ->rawColumns(['action', 'is_paid'])
                ->toJson();
        }

        return view('purchases.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $factories = Factory::all();
        $products = Product::all();
        return view('purchases.create', compact('factories', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        DB::transaction(function () use ($request) {
            $grandTotal = 0;
            $totalDebt = 0;

            foreach ($request->items as $item) {
                $grandTotal += $item['price'];
                $totalDebt += $item['debt_amount'];
            }

            $purchase = Purchase::create([
                'factory_id' => $request->factory_id,
                'date' => $request->date,
                'grand_total' => $grandTotal,
                'total_paid' => $grandTotal - $totalDebt,
                'total_debt' => $totalDebt,
                'is_paid' => $totalDebt == 0,
            ]);

            foreach ($request->items as $item) {
                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'weight' => $item['weight'],
                    'rejected_weight' => $item['rejected_weight'],
                    'price' => $item['price'],
                    'debt_amount' => $item['debt_amount'],
                    'is_printable' => isset($item['is_printable']) ? $item['is_printable'] : false,
                ]);
            }
        });

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load('items.product', 'factory');
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $factories = Factory::all();
        $products = Product::all();
        $purchase->load('items');
        return view('purchases.edit', compact('purchase', 'factories', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        DB::transaction(function () use ($request, $purchase) {
            $purchase->items()->delete();

            $grandTotal = 0;
            $totalDebt = 0;

            foreach ($request->items as $item) {
                $grandTotal += $item['price'];
                $totalDebt += $item['debt_amount'];
            }

            $purchase->update([
                'factory_id' => $request->factory_id,
                'date' => $request->date,
                'grand_total' => $grandTotal,
                'total_paid' => $grandTotal - $totalDebt,
                'total_debt' => $totalDebt,
                'is_paid' => $totalDebt == 0,
            ]);

            foreach ($request->items as $item) {
                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'weight' => $item['weight'],
                    'rejected_weight' => $item['rejected_weight'],
                    'price' => $item['price'],
                    'debt_amount' => $item['debt_amount'],
                    'is_printable' => isset($item['is_printable']) ? $item['is_printable'] : false,
                ]);
            }
        });

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase deleted successfully.');
    }
}
