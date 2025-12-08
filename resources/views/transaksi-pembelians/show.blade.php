@extends('layouts.app')

@section('title', __('Detail Transaksi Pembelian'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Detail Transaksi Pembelian') }}</h3>
                    <p class="text-subtitle text-muted">{{ __('Lihat detail transaksi.') }}</p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transaksi-pembelians.index') }}">{{ __('Transaksi Pembelian') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Detail') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Informasi Transaksi') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">{{ __('Tanggal') }}</th>
                                            <td>: {{ $transaksiPembelian->tanggal_transaksi->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Pabrik') }}</th>
                                            <td>: {{ $transaksiPembelian->factory->name ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">{{ __('Grand Total') }}</th>
                                            <td>: Rp {{ number_format($transaksiPembelian->grand_total, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Status') }}</th>
                                            <td>: 
                                                @if($transaksiPembelian->status_lunas)
                                                    <span class="badge bg-success">Lunas</span>
                                                @else
                                                    <span class="badge bg-warning">Belum Lunas</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Detail Item') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('No') }}</th>
                                            <th>{{ __('Paket') }}</th>
                                            <th>{{ __('Qty') }}</th>
                                            <th>{{ __('Harga/Unit') }}</th>
                                            <th>{{ __('Total') }}</th>
                                            <th>{{ __('Bayar') }}</th>
                                            <th>{{ __('Hutang') }}</th>
                                            <th>{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaksiPembelian->details as $index => $detail)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $detail->paket->name ?? '-' }}</td>
                                            <td>{{ $detail->qty }}</td>
                                            <td>Rp {{ number_format($detail->harga_per_unit, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($detail->total_bayar, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($detail->hutang, 0, ',', '.') }}</td>
                                            <td>
                                                @if($detail->status_lunas)
                                                    <span class="badge bg-success">Lunas</span>
                                                @else
                                                    <span class="badge bg-warning">Belum Lunas</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">{{ __('Total:') }}</th>
                                            <th>Rp {{ number_format($transaksiPembelian->details->sum('total'), 0, ',', '.') }}</th>
                                            <th>Rp {{ number_format($transaksiPembelian->details->sum('total_bayar'), 0, ',', '.') }}</th>
                                            <th>Rp {{ number_format($transaksiPembelian->details->sum('total') - $transaksiPembelian->details->sum('total_bayar'), 0, ',', '.') }}</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('transaksi-pembelians.index') }}" class="btn btn-secondary">{{ __('Kembali') }}</a>
                    <a href="{{ route('transaksi-pembelians.edit', $transaksiPembelian) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                </div>
            </div>
        </section>
    </div>
@endsection
