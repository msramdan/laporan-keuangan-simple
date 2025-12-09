@extends('layouts.app')

@section('title', __('Ringkasan Mesin'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Ringkasan Mesin') }}</h3>
                    <p class="text-subtitle text-muted">{{ __('Ringkasan transaksi mesin.') }}</p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Ringkasan') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header"><h4>{{ __('Filter') }}</h4></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label>{{ __('Tanggal Mulai') }}</label>
                            <input type="date" name="start_date" class="form-control filter-input" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label>{{ __('Tanggal Akhir') }}</label>
                            <input type="date" name="end_date" class="form-control filter-input" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label>{{ __('Client') }}</label>
                            <select name="client_id" class="form-select filter-input">
                                <option value="">-- Semua Client --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>{{ __('Mesin') }}</label>
                            <select name="mesin_id" class="form-select filter-input">
                                <option value="">-- Semua Mesin --</option>
                                @foreach($mesins as $mesin)
                                    <option value="{{ $mesin->id }}" {{ request('mesin_id') == $mesin->id ? 'selected' : '' }}>{{ $mesin->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <a href="{{ route('ringkasan-mesin.index') }}" class="btn btn-outline-secondary mt-2"><i class="bi bi-x-circle"></i> {{ __('Reset') }}</a>
                </div>
            </div>

            <!-- Summary -->
            <div class="row">
                <div class="col-6 col-lg-3"><div class="card"><div class="card-body"><h6 class="text-muted">Total Transaksi</h6><h4>{{ $summary['total_transaksi'] }}</h4></div></div></div>
                <div class="col-6 col-lg-3"><div class="card"><div class="card-body"><h6 class="text-muted">Total Harga Pabrik</h6><h4>Rp {{ number_format($summary['total_harga_pabrik'], 0, ',', '.') }}</h4></div></div></div>
                <div class="col-6 col-lg-3"><div class="card"><div class="card-body"><h6 class="text-muted">Total Harga Jual</h6><h4>Rp {{ number_format($summary['total_harga_jual'], 0, ',', '.') }}</h4></div></div></div>
                <div class="col-6 col-lg-3"><div class="card"><div class="card-body"><h6 class="text-muted">Profit</h6><h4 class="text-success">Rp {{ number_format($summary['total_profit'], 0, ',', '.') }}</h4></div></div></div>
            </div>

            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr><th>No</th><th>Tanggal</th><th>Client</th><th>Mesin</th><th>Produk</th><th>Harga Pabrik</th><th>Harga Jual</th><th>Profit</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $i => $t)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $t->tanggal_transaksi->format('d/m/Y') }}</td>
                                    <td>{{ $t->client->name ?? '-' }}</td>
                                    <td>{{ $t->mesin->name ?? '-' }}</td>
                                    <td>{{ $t->nama_produk }}</td>
                                    <td>Rp {{ number_format($t->harga_pabrik, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($t->harga_jual, 0, ',', '.') }}</td>
                                    <td class="text-success">Rp {{ number_format($t->harga_jual - $t->harga_pabrik, 0, ',', '.') }}</td>
                                    <td>@if($t->status_lunas)<span class="badge bg-success">Lunas</span>@else<span class="badge bg-warning">Belum Lunas</span>@endif</td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="text-center">Tidak ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
<script>
    document.querySelectorAll('.filter-input').forEach(function(input) {
        input.addEventListener('change', function() {
            const url = new URL(window.location.href);
            const name = this.getAttribute('name');
            const value = this.value;
            
            if (value) {
                url.searchParams.set(name, value);
            } else {
                url.searchParams.delete(name);
            }
            
            window.location.href = url.toString();
        });
    });
</script>
@endpush
