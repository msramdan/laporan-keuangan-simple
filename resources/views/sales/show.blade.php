@extends('layouts.app')

@section('title', __('Nota Pembeli'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Nota Pembeli') }}</h3>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">{{ __('Sales') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Nota') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{ __('Invoice') }}</h4>
                    <button onclick="window.print()" class="btn btn-secondary">
                        <i class="bi bi-printer"></i> {{ __('Print Nota') }}
                    </button>
                </div>
                <div class="card-body" id="printable-area">
                    <div class="text-center mb-5">
                        <h2 class="text-uppercase font-bold">{{ __('NOTA PEMBELIAN') }}</h2>
                        <p class="text-muted">{{ __('Tanggal') }}: {{ $sale->date->format('d F Y') }}</p>
                    </div>

                    <div class="mb-4">
                        <p><strong>{{ __('Nama Pembeli') }}:</strong> {{ $sale->buyer_name }}</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>{{ __('Produk') }}</th>
                                    <th>{{ __('Berat') }}</th>
                                    <th class="text-end">{{ __('Harga') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sale->items as $item)
                                    <tr>
                                        <td>{{ $item->purchaseItem->product->name }}</td>
                                        <td>{{ $item->purchaseItem->weight }} {{ $item->purchaseItem->product->unit }}</td>
                                        <td class="text-end">Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold bg-light">
                                    <td colspan="2">{{ __('Total') }}</td>
                                    <td class="text-end">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-5 text-center text-muted">
                        <p>{{ __('Terima kasih atas kunjungan Anda.') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printable-area, #printable-area * {
                visibility: visible;
            }
            #printable-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .card {
                border: none;
                box-shadow: none;
            }
        }
    </style>
@endsection
