<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Financial Summary
        $totalIncome = Fund::where('type', 'IN')->sum('amount');
        $totalExpense = Fund::where('type', 'OUT')->sum('amount');
        $totalDebt = Purchase::sum('total_debt');
        $totalReceivable = Sale::sum('total_receivable');
        
        $totalSales = Sale::sum('grand_total');
        $totalPurchases = Purchase::sum('grand_total');
        $totalProfit = $totalSales - $totalPurchases;
        
        // Recent Transactions
        $recentPurchases = Purchase::with('factory')->latest()->take(5)->get();
        $recentSales = Sale::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'totalDebt',
            'totalReceivable',
            'totalProfit',
            'recentPurchases',
            'recentSales'
        ));
    }
}
