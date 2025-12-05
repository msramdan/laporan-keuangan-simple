@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <div class="page-heading">
        <h3>{{ __('Dashboard') }}</h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-12">
                <!-- Statistics -->
                <div class="row">
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">{{ __('Total Pemasukan') }}</h6>
                                        <h6 class="font-extrabold mb-0">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon red">
                                            <i class="iconly-boldBookmark"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">{{ __('Total Pengeluaran') }}</h6>
                                        <h6 class="font-extrabold mb-0">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon red">
                                            <i class="iconly-boldPaper-Negative"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">{{ __('Total Hutang Pabrik') }}</h6>
                                        <h6 class="font-extrabold mb-0">Rp {{ number_format($totalDebt, 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon orange">
                                            <i class="iconly-boldWallet"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">{{ __('Total Piutang') }}</h6>
                                        <h6 class="font-extrabold mb-0">Rp {{ number_format($totalReceivable, 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon {{ $totalProfit >= 0 ? 'green' : 'red' }}">
                                            <i class="iconly-boldGraph"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">{{ __('Laba Rugi') }}</h6>
                                        <h6 class="font-extrabold mb-0">Rp {{ number_format($totalProfit, 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="row">
                    <div class="col-12 col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Recent Purchases (Nota Pabrik)') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-lg">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Factory') }}</th>
                                                <th>{{ __('Total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recentPurchases as $purchase)
                                                <tr>
                                                    <td class="col-3">{{ $purchase->date->format('d/m/Y') }}</td>
                                                    <td class="col-5">{{ $purchase->factory->name }}</td>
                                                    <td class="col-4">Rp {{ number_format($purchase->grand_total, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Recent Sales (Nota Pembeli)') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-lg">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Buyer') }}</th>
                                                <th>{{ __('Total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recentSales as $sale)
                                                <tr>
                                                    <td class="col-3">{{ $sale->date->format('d/m/Y') }}</td>
                                                    <td class="col-5">{{ $sale->buyer_name }}</td>
                                                    <td class="col-4">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
