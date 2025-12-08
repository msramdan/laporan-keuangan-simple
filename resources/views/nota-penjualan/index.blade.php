@extends('layouts.app')

@section('title', __('Cetak Nota Penjualan'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Cetak Nota Penjualan') }}</h3>
                    <p class="text-subtitle text-muted">{{ __('Pilih transaksi untuk dicetak.') }}</p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Nota Penjualan') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <form action="{{ route('nota-penjualan.print') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header"><h4>{{ __('Filter') }}</h4></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ __('Tanggal Mulai') }}</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label>{{ __('Tanggal Akhir') }}</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label>{{ __('Client') }}</label>
                                <select name="client_id" class="form-select">
                                    <option value="">-- Semua --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>{{ __('Mesin') }}</label>
                                <select name="mesin_id" class="form-select">
                                    <option value="">-- Semua --</option>
                                    @foreach($mesins as $mesin)
                                        <option value="{{ $mesin->id }}" {{ request('mesin_id') == $mesin->id ? 'selected' : '' }}>{{ $mesin->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <a href="{{ route('nota-penjualan.index', request()->all()) }}" class="btn btn-primary mt-2"><i class="bi bi-search"></i> Filter</a>
                        <button type="submit" class="btn btn-success mt-2"><i class="bi bi-printer"></i> Cetak Terpilih</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>Tanggal</th><th>Client</th><th>Mesin</th><th>Produk</th><th>Harga Jual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksis as $t)
                                    <tr>
                                        <td><input type="checkbox" name="selected[]" value="{{ $t->id }}" class="item-checkbox"></td>
                                        <td>{{ $t->tanggal_transaksi->format('d/m/Y') }}</td>
                                        <td>{{ $t->client->name ?? '-' }}</td>
                                        <td>{{ $t->mesin->name ?? '-' }}</td>
                                        <td>{{ $t->nama_produk }}</td>
                                        <td>Rp {{ number_format($t->harga_jual, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('js')
<script>
    document.getElementById('select-all').addEventListener('change', function() {
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
@endpush
