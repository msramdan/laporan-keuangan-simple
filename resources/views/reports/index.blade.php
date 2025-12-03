@extends('layouts.app')

@section('title', __('Reports'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Reports') }}</h3>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Reports') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('reports.index') }}" class="mb-8 p-4 bg-gray-50 rounded border no-print">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">{{ __('Start Date') }}</label>
                                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">{{ __('End Date') }}</label>
                                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">{{ __('Factory') }}</label>
                                <select name="factory_id" class="form-select">
                                    <option value="">{{ __('All Factories') }}</option>
                                    @foreach ($factories as $factory)
                                        <option value="{{ $factory->id }}" {{ $factoryId == $factory->id ? 'selected' : '' }}>{{ $factory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">{{ __('Product') }}</label>
                                <select name="product_id" class="form-select">
                                    <option value="">{{ __('All Products') }}</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ $productId == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-filter"></i> {{ __('Filter') }}
                            </button>
                            <a href="{{ route('reports.print', request()->all()) }}" target="_blank" class="btn btn-secondary">
                                <i class="bi bi-printer"></i> {{ __('Print PDF') }}
                            </a>
                        </div>
                    </form>

                    <!-- Report Content -->
                    <div class="printable-area p-4">
                        <div class="text-center mb-5">
                            <h2 class="text-uppercase fw-bold">{{ __('Laporan Keuangan') }}</h2>
                            <p class="text-muted">{{ __('Periode') }}: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                        </div>

                        <!-- Detailed Report -->
                        @foreach ($groupedPurchases as $fId => $purchases)
                            @php $factoryName = $purchases->first()->factory->name; @endphp
                            <div class="mb-5">
                                <h4 class="fw-bold border-bottom pb-2 mb-3">{{ $factoryName }}</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center" width="15%">{{ __('Date') }}</th>
                                                <th>{{ __('Product') }}</th>
                                                <th class="text-end" width="20%">{{ __('Price (Beli)') }}</th>
                                                <th class="text-end" width="20%">{{ __('Debt (Hutang)') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($purchases as $purchase)
                                                @foreach ($purchase->items as $item)
                                                    @if (!$productId || $item->product_id == $productId)
                                                        <tr>
                                                            <td class="text-center">{{ $purchase->date->format('d/m/Y') }}</td>
                                                            <td>{{ $item->product->name }} ({{ $item->weight }} {{ $item->product->unit }})</td>
                                                            <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                            <td class="text-end text-danger">Rp {{ number_format($item->debt_amount, 0, ',', '.') }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach

                        <!-- Summary Report -->
                        <div class="mt-5 page-break-before">
                            <h3 class="fw-bold mb-4">{{ __('Summary') }}</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>{{ __('Factory') }}</th>
                                            <th class="text-end">{{ __('Total Pembelian') }}</th>
                                            <th class="text-end">{{ __('Total Hutang') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($summary as $s)
                                            <tr>
                                                <td class="fw-medium">{{ $s['name'] }}</td>
                                                <td class="text-end">Rp {{ number_format($s['total_purchase'], 0, ',', '.') }}</td>
                                                <td class="text-end text-danger">Rp {{ number_format($s['total_debt'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light fw-bold">
                                        <tr>
                                            <td>{{ __('Grand Total') }}</td>
                                            <td class="text-end">Rp {{ number_format($grandTotalPurchase, 0, ',', '.') }}</td>
                                            <td class="text-end text-danger">Rp {{ number_format($grandTotalDebt, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white;
                font-size: 12pt;
            }
            .shadow-sm {
                box-shadow: none !important;
            }
            .page-break-before {
                page-break-before: always;
            }
            .card {
                border: none;
            }
            a {
                text-decoration: none;
                color: black;
            }
        }
    </style>
@endsection
