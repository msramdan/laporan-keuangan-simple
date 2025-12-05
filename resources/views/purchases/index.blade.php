@extends('layouts.app')

@section('title', __('Purchases'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Purchases (Nota Pabrik)') }}</h3>
                    <p class="text-subtitle text-muted">
                        {{ __('Below is a list of all purchases.') }}
                    </p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Purchases') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <x-alert></x-alert>

            <div class="d-flex justify-content-end mb-3">
                <button id="btn-bulk-pay" class="btn btn-success me-2" disabled>
                    <i class="fas fa-money-bill-wave"></i> {{ __('Bayar Terpilih') }}
                </button>
                <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('Buat Pembelian Baru') }}
                </a>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive p-1">
                                <table class="table table-striped" id="data-table" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%"><input type="checkbox" id="select-all" class="form-check-input"></th>
                                            <th>{{ __('No') }}</th>
                                            <th>{{ __('Tanggal') }}</th>
                                            <th>{{ __('Pabrik') }}</th>
                                            <th>{{ __('Total Harga') }}</th>
                                            <th>{{ __('Total Hutang') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Dibuat Pada') }}</th>
                                            <th>{{ __('Diupdate Pada') }}</th>
                                            <th>{{ __('Aksi') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.12.0/datatables.min.css" />
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.12.0/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('purchases.index') }}",
            columns: [
                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'factory_name',
                    name: 'factory_name'
                },
                {
                    data: 'grand_total',
                    name: 'grand_total'
                },
                {
                    data: 'total_debt',
                    name: 'total_debt'
                },
                {
                    data: 'is_paid',
                    name: 'is_paid'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
        });

        // Select All Checkbox
        $('#select-all').on('click', function() {
            $('.purchase-checkbox').prop('checked', this.checked);
            toggleBulkPayButton();
        });

        // Individual Checkbox
        $(document).on('click', '.purchase-checkbox', function() {
            if ($('.purchase-checkbox:checked').length == $('.purchase-checkbox').length) {
                $('#select-all').prop('checked', true);
            } else {
                $('#select-all').prop('checked', false);
            }
            toggleBulkPayButton();
        });

        function toggleBulkPayButton() {
            if ($('.purchase-checkbox:checked').length > 0) {
                $('#btn-bulk-pay').prop('disabled', false);
            } else {
                $('#btn-bulk-pay').prop('disabled', true);
            }
        }

        // Bulk Pay Action
        $('#btn-bulk-pay').on('click', function() {
            const selectedIds = [];
            $('.purchase-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) return;

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan melunasi nota pembelian yang dipilih!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lunasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('purchases.bulk-pay') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            ids: selectedIds
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Berhasil!',
                                    'Nota pembelian yang dipilih telah dilunasi.',
                                    'success'
                                );
                                table.ajax.reload();
                                $('#select-all').prop('checked', false);
                                toggleBulkPayButton();
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat memproses permintaan.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan: ' + xhr.responseText,
                                'error'
                            );
                        }
                    });
                }
            })
        });
    </script>
@endpush
