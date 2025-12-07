@extends('layouts.app')

@section('title', __('Edit Transaksi Pembelian'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Edit Transaksi Pembelian') }}</h3>
                    <p class="text-subtitle text-muted">{{ __('Perbarui transaksi pembelian.') }}</p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transaksi-pembelians.index') }}">{{ __('Transaksi Pembelian') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <form action="{{ route('transaksi-pembelians.update', $transaksiPembelian) }}" method="POST" id="transaction-form">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><h4>{{ __('Informasi Transaksi') }}</h4></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="factory_id">{{ __('Pabrik') }} <span class="text-danger">*</span></label>
                                            <select name="factory_id" id="factory_id" class="form-select" required>
                                                <option value="">-- Pilih Pabrik --</option>
                                                @foreach($factories as $factory)
                                                    <option value="{{ $factory->id }}" {{ $transaksiPembelian->factory_id == $factory->id ? 'selected' : '' }}>
                                                        {{ $factory->code }} - {{ $factory->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_transaksi">{{ __('Tanggal Transaksi') }} <span class="text-danger">*</span></label>
                                            <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" 
                                                class="form-control" value="{{ $transaksiPembelian->tanggal_transaksi->format('Y-m-d') }}" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>{{ __('Detail Item') }}</h4>
                                <button type="button" class="btn btn-success btn-sm" id="add-item">
                                    <i class="fas fa-plus"></i> {{ __('Tambah Item') }}
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="items-table">
                                        <thead>
                                            <tr>
                                                <th width="25%">{{ __('Paket') }}</th>
                                                <th width="12%">{{ __('Qty') }}</th>
                                                <th width="18%">{{ __('Harga/Unit') }}</th>
                                                <th width="15%">{{ __('Total') }}</th>
                                                <th width="18%">{{ __('Bayar') }}</th>
                                                <th width="7%">{{ __('Aksi') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="items-body">
                                            @foreach($transaksiPembelian->details as $index => $detail)
                                            <tr class="item-row" data-index="{{ $index }}">
                                                <td>
                                                    <input type="hidden" name="details[{{ $index }}][id]" value="{{ $detail->id }}">
                                                    <select name="details[{{ $index }}][paket_id]" class="form-select paket-select" required>
                                                        <option value="">-- Pilih Paket --</option>
                                                        @foreach($pakets as $paket)
                                                            <option value="{{ $paket->id }}" {{ $detail->paket_id == $paket->id ? 'selected' : '' }}>
                                                                {{ $paket->code }} - {{ $paket->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="number" name="details[{{ $index }}][qty]" class="form-control qty-input" value="{{ $detail->qty }}" min="1" required /></td>
                                                <td><input type="text" name="details[{{ $index }}][harga_per_unit]" class="form-control harga-input" data-format="number" value="{{ $detail->harga_per_unit }}" required /></td>
                                                <td><input type="text" class="form-control total-display" value="{{ number_format($detail->total, 0, ',', '.') }}" readonly /></td>
                                                <td><input type="text" name="details[{{ $index }}][total_bayar]" class="form-control bayar-input" data-format="number" value="{{ $detail->total_bayar }}" /></td>
                                                <td><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fas fa-trash"></i></button></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end">{{ __('Grand Total:') }}</th>
                                                <th id="grand-total">Rp 0</th>
                                                <th id="total-bayar">Rp 0</th>
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
                        <button type="submit" class="btn btn-primary">{{ __('Perbarui Transaksi') }}</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        let itemIndex = {{ $transaksiPembelian->details->count() }};
        const pakets = @json($pakets);

        function formatRupiah(number) {
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        function formatNumber(value) {
            if (value === '' || value === null || isNaN(value)) return '0';
            let num = parseFloat(value);
            if (isNaN(num)) return '0';
            let formattedValue = Number.isInteger(num) ? num.toString() : num.toFixed(2).replace(/\.?0+$/, '');
            let parts = formattedValue.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return parts.join(',');
        }

        function parseNumber(value) {
            if (!value || value === '') return 0;
            return parseFloat(value.toString().replace(/\./g, '').replace(/,/g, '.')) || 0;
        }

        function calculateRow(row) {
            const qty = parseInt($(row).find('.qty-input').val()) || 0;
            const harga = parseNumber($(row).find('.harga-input').val());
            const total = qty * harga;
            $(row).find('.total-display').val(formatNumber(total));
        }

        function calculateTotals() {
            let grandTotal = 0;
            let totalBayar = 0;
            $('.item-row').each(function() {
                const qty = parseInt($(this).find('.qty-input').val()) || 0;
                const harga = parseNumber($(this).find('.harga-input').val());
                const bayar = parseNumber($(this).find('.bayar-input').val());
                grandTotal += qty * harga;
                totalBayar += bayar;
            });
            $('#grand-total').text(formatRupiah(grandTotal));
            $('#total-bayar').text(formatRupiah(totalBayar));
        }

        function initFormatInputs() {
            $('[data-format="number"]').each(function() {
                if (!$(this).data('formatted')) {
                    $(this).data('formatted', true);
                    var val = parseNumber($(this).val());
                    $(this).val(formatNumber(val));
                }
            });
        }

        $(document).ready(function() {
            // Initialize formatting
            initFormatInputs();

            $('#add-item').click(function() {
                let paketOptions = '<option value="">-- Pilih Paket --</option>';
                pakets.forEach(p => { paketOptions += `<option value="${p.id}">${p.code} - ${p.name}</option>`; });
                const newRow = `
                    <tr class="item-row" data-index="${itemIndex}">
                        <td><select name="details[${itemIndex}][paket_id]" class="form-select paket-select" required>${paketOptions}</select></td>
                        <td><input type="number" name="details[${itemIndex}][qty]" class="form-control qty-input" value="1" min="1" required /></td>
                        <td><input type="text" name="details[${itemIndex}][harga_per_unit]" class="form-control harga-input" data-format="number" value="0" required /></td>
                        <td><input type="text" class="form-control total-display" value="0" readonly /></td>
                        <td><input type="text" name="details[${itemIndex}][total_bayar]" class="form-control bayar-input" data-format="number" value="0" /></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fas fa-trash"></i></button></td>
                    </tr>
                `;
                $('#items-body').append(newRow);
                initFormatInputs();
                itemIndex++;
            });

            $(document).on('click', '.remove-item', function() {
                if ($('.item-row').length > 1) {
                    $(this).closest('tr').remove();
                    calculateTotals();
                } else {
                    alert('Minimal harus ada 1 item!');
                }
            });

            $(document).on('input', '.qty-input', function() {
                calculateRow($(this).closest('tr'));
                calculateTotals();
            });

            $(document).on('blur', '.harga-input, .bayar-input', function() {
                var val = parseNumber($(this).val());
                $(this).val(formatNumber(val));
                calculateRow($(this).closest('tr'));
                calculateTotals();
            });

            $(document).on('focus', '.harga-input, .bayar-input', function() {
                var val = parseNumber($(this).val());
                $(this).val(val || '');
                $(this).select();
            });

            // Handle form submit - convert formatted values to raw
            $('#transaction-form').on('submit', function() {
                $('[data-format="number"]').each(function() {
                    $(this).val(parseNumber($(this).val()));
                });
            });

            calculateTotals();
        });
    </script>
@endpush
