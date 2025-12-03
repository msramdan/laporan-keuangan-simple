<?php

namespace App\Http\Controllers;

use App\Models\Factory;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getReportData($request);
        return view('reports.index', $data);
    }

    public function print(Request $request)
    {
        $data = $this->getReportData($request);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.print', $data);
        return $pdf->stream('laporan-keuangan-' . date('Y-m-d') . '.pdf');
    }

    private function getReportData(Request $request)
    {
        $factories = Factory::all();
        $products = Product::all();

        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));
        $factoryId = $request->input('factory_id');
        $productId = $request->input('product_id');

        $query = Purchase::with(['factory', 'items.product'])
            ->whereBetween('date', [$startDate, $endDate]);

        if ($factoryId) {
            $query->where('factory_id', $factoryId);
        }

        if ($productId) {
            $query->whereHas('items', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            });
        }

        $purchases = $query->orderBy('factory_id')->orderBy('date')->get();

        // Group by Factory
        $groupedPurchases = $purchases->groupBy('factory_id');

        // Calculate Summaries
        $summary = [];
        $grandTotalPurchase = 0;
        $grandTotalDebt = 0;

        foreach ($groupedPurchases as $factoryId => $factoryPurchases) {
            $factoryName = $factoryPurchases->first()->factory->name;
            $totalPurchase = $factoryPurchases->sum('grand_total');
            $totalDebt = $factoryPurchases->sum('total_debt');

            $summary[$factoryId] = [
                'name' => $factoryName,
                'total_purchase' => $totalPurchase,
                'total_debt' => $totalDebt,
            ];

            $grandTotalPurchase += $totalPurchase;
            $grandTotalDebt += $totalDebt;
        }

        return compact(
            'factories',
            'products',
            'groupedPurchases',
            'summary',
            'grandTotalPurchase',
            'grandTotalDebt',
            'startDate',
            'endDate',
            'factoryId',
            'productId'
        );
    }
}
