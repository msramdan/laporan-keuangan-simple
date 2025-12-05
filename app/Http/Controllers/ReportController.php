<?php

namespace App\Http\Controllers;

use App\Models\Factory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SaleItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportController extends Controller
{
    /**
     * Display the report index page.
     */
    public function index(Request $request)
    {
        $data = $this->getReportData($request);
        return view('reports.index', $data);
    }

    /**
     * Generate and stream the report PDF.
     */
    public function print(Request $request)
    {
        $data = $this->getReportData($request);
        $pdf = Pdf::loadView('reports.print', $data);
        return $pdf->stream('laporan-keuangan-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Retrieve and organize report data.
     */
    private function getReportData(Request $request): array
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));
        $factoryId = $request->input('factory_id');
        $productId = $request->input('product_id');

        $factories = Factory::all();
        $products = Product::all();

        $groupedPurchases = $this->getGroupedPurchases($startDate, $endDate, $factoryId, $productId);
        $groupedSales = $this->getGroupedSales($startDate, $endDate);

        $summary = $this->calculateSummary($groupedPurchases, $groupedSales);

        // Calculate Grand Totals
        $grandTotalPurchase = $summary->sum('total_purchase');
        $grandTotalDebt = $summary->sum('total_debt');
        $grandTotalSale = $summary->sum('total_sale');
        $grandTotalProfit = $summary->sum('profit');

        return compact(
            'factories',
            'products',
            'groupedPurchases',
            'summary',
            'grandTotalPurchase',
            'grandTotalDebt',
            'grandTotalSale',
            'grandTotalProfit',
            'startDate',
            'endDate',
            'factoryId',
            'productId'
        );
    }

    /**
     * Get purchases grouped by factory.
     */
    private function getGroupedPurchases(string $startDate, string $endDate, ?string $factoryId, ?string $productId): Collection
    {
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

        return $query->orderBy('factory_id')->orderBy('date')->get()->groupBy('factory_id');
    }

    /**
     * Get sales grouped by factory (via purchase item).
     */
    private function getGroupedSales(string $startDate, string $endDate): Collection
    {
        return SaleItem::with(['purchaseItem.purchase.factory'])
            ->whereHas('sale', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            })
            ->get()
            ->groupBy(function ($item) {
                return $item->purchaseItem->purchase->factory_id ?? 'Unknown';
            });
    }

    /**
     * Calculate summary statistics per factory.
     */
    private function calculateSummary(Collection $groupedPurchases, Collection $groupedSales): Collection
    {
        $allFactoryIds = $groupedPurchases->keys()->merge($groupedSales->keys())->unique();

        return $allFactoryIds->map(function ($fId) use ($groupedPurchases, $groupedSales) {
            $factoryPurchases = $groupedPurchases->get($fId, collect());
            $factorySales = $groupedSales->get($fId, collect());

            $factoryName = $this->resolveFactoryName($factoryPurchases, $factorySales);

            $totalPurchase = $factoryPurchases->sum('grand_total');
            $totalDebt = $factoryPurchases->sum('total_debt');
            $totalSale = $factorySales->sum('selling_price');
            $profit = $totalSale - $totalPurchase;

            return [
                'id' => $fId,
                'name' => $factoryName,
                'total_purchase' => $totalPurchase,
                'total_debt' => $totalDebt,
                'total_sale' => $totalSale,
                'profit' => $profit,
            ];
        });
    }

    /**
     * Resolve factory name from purchases or sales.
     */
    private function resolveFactoryName(Collection $purchases, Collection $sales): string
    {
        if ($purchases->isNotEmpty()) {
            return $purchases->first()->factory->name;
        }

        if ($sales->isNotEmpty()) {
            return $sales->first()->purchaseItem->purchase->factory->name ?? 'Unknown';
        }

        return 'Unknown';
    }
}
