@extends('layouts.app')

@section('title', __('Dashboard'))

@push('css')
    <style>
        .stats-icon {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 60px !important;
            height: 60px !important;
            border-radius: 0.5rem;
            margin: 0 auto;
        }

        .stats-icon i {
            font-size: 28px !important;
            line-height: 1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .card-body .row {
            align-items: center;
        }

        .col-md-4 {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <h3>{{ __('Dashboard') }}</h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-12">
                <!-- Master Data Statistics -->
                <div class="row">
                    @can('factory create')
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon purple">
                                                <i class="bi bi-building"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">{{ __('Total Pabrik') }}</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totalPabrik }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan
                    @can('paket create')
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon blue">
                                                <i class="bi bi-box-seam"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">{{ __('Total Paket') }}</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totalPaket }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="bi bi-people"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">{{ __('Total Client') }}</h6>
                                        <h6 class="font-extrabold mb-0">{{ $totalClient }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon red">
                                            <i class="bi bi-gear"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">{{ __('Total Mesin') }}</h6>
                                        <h6 class="font-extrabold mb-0">{{ $totalMesin }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Financial Statistics -->
                <div class="row">
                    @can('transaksi pembelian create')
                        <div class="col-6 col-lg-4 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon green">
                                                <i class="bi bi-cart-plus"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">{{ __('Total Pembelian') }}</h6>
                                            <h6 class="font-extrabold mb-0">Rp
                                                {{ number_format($totalNilaiPembelian, 0, ',', '.') }}</h6>
                                            <small class="text-muted">{{ $totalTransaksiPembelian }} transaksi</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="bi bi-cash-coin"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">{{ __('Total Penjualan Mesin') }}</h6>
                                        <h6 class="font-extrabold mb-0">Rp
                                            {{ number_format($totalHargaJual, 0, ',', '.') }}</h6>
                                        <small class="text-muted">{{ $totalTransaksiMesin }} transaksi</small>
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
                                            <i class="bi bi-graph-up-arrow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">{{ __('Total Profit Mesin') }}</h6>
                                        <h6 class="font-extrabold mb-0">Rp {{ number_format($totalProfit, 0, ',', '.') }}
                                        </h6>
                                        <small class="text-{{ $pembelianBelumLunas > 0 ? 'warning' : 'success' }}">
                                            {{ $pembelianBelumLunas }} pembelian belum lunas
                                        </small>
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
                                <h4>{{ __('Transaksi Pembelian Terbaru') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-lg">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Tanggal') }}</th>
                                                <th>{{ __('Pabrik') }}</th>
                                                <th>{{ __('Total') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($recentPembelian as $pembelian)
                                                <tr>
                                                    <td>{{ $pembelian->tanggal_transaksi->format('d/m/Y') }}</td>
                                                    <td>{{ $pembelian->factory->name ?? '-' }}</td>
                                                    <td>Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if ($pembelian->status_lunas)
                                                            <span class="badge bg-success">Lunas</span>
                                                        @else
                                                            <span class="badge bg-warning">Belum Lunas</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">{{ __('Belum ada data') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Transaksi Mesin Terbaru') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-lg">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Tanggal') }}</th>
                                                <th>{{ __('Client') }}</th>
                                                <th>{{ __('Produk') }}</th>
                                                <th>{{ __('Harga Jual') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($recentMesin as $mesin)
                                                <tr>
                                                    <td>{{ $mesin->tanggal_transaksi->format('d/m/Y') }}</td>
                                                    <td>{{ $mesin->client->name ?? '-' }}</td>
                                                    <td>{{ $mesin->nama_produk }}</td>
                                                    <td>Rp {{ number_format($mesin->harga_jual, 0, ',', '.') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">{{ __('Belum ada data') }}</td>
                                                </tr>
                                            @endforelse
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
