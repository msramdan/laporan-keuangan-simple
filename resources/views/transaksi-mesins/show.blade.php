@extends('layouts.app')

@section('title', __('Detail Transaksi Mesin'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Detail Transaksi Mesin') }}</h3>
                    <p class="text-subtitle text-muted">{{ __('Lihat detail transaksi.') }}</p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transaksi-mesins.index') }}">{{ __('Transaksi Mesin') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Detail') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><th width="40%">{{ __('Tanggal') }}</th><td>: {{ $transaksiMesin->tanggal_transaksi->format('d/m/Y') }}</td></tr>
                                <tr><th>{{ __('Client') }}</th><td>: {{ $transaksiMesin->client->name ?? '-' }}</td></tr>
                                <tr><th>{{ __('Mesin') }}</th><td>: {{ $transaksiMesin->mesin->name ?? '-' }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><th width="40%">{{ __('Nama Produk') }}</th><td>: {{ $transaksiMesin->nama_produk }}</td></tr>
                                <tr><th>{{ __('Harga Pabrik') }}</th><td>: Rp {{ number_format($transaksiMesin->harga_pabrik, 0, ',', '.') }}</td></tr>
                                <tr><th>{{ __('Harga Jual') }}</th><td>: Rp {{ number_format($transaksiMesin->harga_jual, 0, ',', '.') }}</td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center" width="33%">{{ __('Total Harga Pabrik') }}</th>
                                    <th class="text-center" width="33%">{{ __('Total Harga Jual') }}</th>
                                    <th class="text-center" width="34%">{{ __('Profit') }}</th>
                                </tr>
                                <tr>
                                    <td class="text-center">Rp {{ number_format($transaksiMesin->total_harga_pabrik, 0, ',', '.') }}</td>
                                    <td class="text-center">Rp {{ number_format($transaksiMesin->total_harga_jual, 0, ',', '.') }}</td>
                                    <td class="text-center text-success">Rp {{ number_format($transaksiMesin->total_harga_jual - $transaksiMesin->total_harga_pabrik, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <strong>{{ __('Status') }}:</strong>
                            @if($transaksiMesin->status_lunas)
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-warning">Belum Lunas</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('transaksi-mesins.index') }}" class="btn btn-secondary">{{ __('Kembali') }}</a>
            <a href="{{ route('transaksi-mesins.edit', $transaksiMesin) }}" class="btn btn-primary">{{ __('Edit') }}</a>
        </section>
    </div>
@endsection
