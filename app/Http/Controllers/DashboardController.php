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
        
        // Recent Transactions
        $recentPurchases = Purchase::with('factory')->latest()->take(5)->get();
        $recentSales = Sale::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'totalDebt',
            'recentPurchases',
            'recentSales'
        ));
    }
}
