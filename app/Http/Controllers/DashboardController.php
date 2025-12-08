<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPembelian;
use App\Models\TransaksiMesin;
use App\Models\Factory;
use App\Models\Client;
use App\Models\Paket;
use App\Models\Mesin;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalPabrik = Factory::count();
        $totalPaket = Paket::count();
        $totalClient = Client::count();
        $totalMesin = Mesin::count();

        // Pembelian Summary
        $totalTransaksiPembelian = TransaksiPembelian::count();
        $totalNilaiPembelian = TransaksiPembelian::sum('grand_total');
        $pembelianBelumLunas = TransaksiPembelian::where('status_lunas', false)->count();

        // Mesin Summary
        $totalTransaksiMesin = TransaksiMesin::count();
        $totalHargaPabrik = TransaksiMesin::sum('total_harga_pabrik');
        $totalHargaJual = TransaksiMesin::sum('total_harga_jual');
        $totalProfit = $totalHargaJual - $totalHargaPabrik;

        // Recent Transactions
        $recentPembelian = TransaksiPembelian::with('factory')->latest()->take(5)->get();
        $recentMesin = TransaksiMesin::with(['client', 'mesin'])->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalPabrik',
            'totalPaket',
            'totalClient',
            'totalMesin',
            'totalTransaksiPembelian',
            'totalNilaiPembelian',
            'pembelianBelumLunas',
            'totalTransaksiMesin',
            'totalHargaPabrik',
            'totalHargaJual',
            'totalProfit',
            'recentPembelian',
            'recentMesin'
        ));
    }
}
