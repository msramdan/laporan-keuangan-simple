@extends('layouts.app')

@section('title', __('Laporan Pembelian'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Laporan Pembelian') }}</h3>
                    <p class="text-subtitle text-muted">{{ __('Ringkasan laporan transaksi pembelian.') }}</p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Laporan Pembelian') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Filter') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan-pembelian.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ __('Tanggal Mulai') }}</label>
                                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ __('Tanggal Akhir') }}</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ __('Pabrik') }}</label>
                                    <select name="factory_id" class="form-select">
                                        <option value="">-- Semua Pabrik --</option>
                                        @foreach($factories as $factory)
                                            <option value="{{ $factory->id }}" {{ request('factory_id') == $factory->id ? 'selected' : '' }}>
                                                {{ $factory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ __('Status') }}</label>
                                    <select name="status_lunas" class="form-select">
                                        <option value="">-- Semua Status --</option>
                                        <option value="lunas" {{ request('status_lunas') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                        <option value="belum_lunas" {{ request('status_lunas') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> {{ __('Filter') }}
                        </button>
                        <a href="{{ route('laporan-pembelian.print', request()->all()) }}" class="btn btn-success" target="_blank">
                            <i class="bi bi-printer"></i> {{ __('Cetak PDF') }}
                        </a>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <h6 class="text-muted font-semibold">{{ __('Total Transaksi') }}</h6>
                            <h6 class="font-extrabold mb-0">{{ $summary['total_transaksi'] }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <h6 class="text-muted font-semibold">{{ __('Total Harga') }}</h6>
                            <h6 class="font-extrabold mb-0">Rp {{ number_format($summary['total_harga'], 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <h6 class="text-muted font-semibold">{{ __('Total Bayar') }}</h6>
                            <h6 class="font-extrabold mb-0">Rp {{ number_format($summary['total_bayar'], 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <h6 class="text-muted font-semibold">{{ __('Total Hutang') }}</h6>
                            <h6 class="font-extrabold mb-0 text-danger">Rp {{ number_format($summary['total_hutang'], 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('No') }}</th>
                                    <th>{{ __('Tanggal') }}</th>
                                    <th>{{ __('Pabrik') }}</th>
                                    <th>{{ __('Grand Total') }}</th>
                                    <th>{{ __('Bayar') }}</th>
                                    <th>{{ __('Hutang') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksis as $index => $transaksi)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $transaksi->tanggal_transaksi->format('d/m/Y') }}</td>
                                        <td>{{ $transaksi->factory->name ?? '-' }}</td>
                                        <td>Rp {{ number_format($transaksi->grand_total, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($transaksi->details->sum('total_bayar'), 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($transaksi->grand_total - $transaksi->details->sum('total_bayar'), 0, ',', '.') }}</td>
                                        <td>
                                            @if($transaksi->status_lunas)
                                                <span class="badge bg-success">Lunas</span>
                                            @else
                                                <span class="badge bg-warning">Belum Lunas</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">{{ __('Tidak ada data') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
