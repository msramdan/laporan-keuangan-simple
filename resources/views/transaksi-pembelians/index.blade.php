@extends('layouts.app')

@section('title', __('Transaksi Pembelian'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Transaksi Pembelian') }}</h3>
                    <p class="text-subtitle text-muted">{{ __('Daftar transaksi pembelian paket.') }}</p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Transaksi Pembelian') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <x-alert></x-alert>

            <div class="d-flex justify-content-end">
                <a href="{{ route('transaksi-pembelians.create') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> {{ __('Tambah Transaksi') }}
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
                                            <th>{{ __('No') }}</th>
                                            <th>{{ __('Tanggal') }}</th>
                                            <th>{{ __('Pabrik') }}</th>
                                            <th>{{ __('Jumlah Item') }}</th>
                                            <th>{{ __('Grand Total') }}</th>
                                            <th>{{ __('Total Bayar') }}</th>
                                            <th>{{ __('Hutang') }}</th>
                                            <th>{{ __('Status') }}</th>
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

    <!-- Detail & Payment Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailModalLabel">
                        <i class="fas fa-file-invoice"></i> Detail & Pembayaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modal-loading" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Memuat data...</p>
                    </div>
                    <div id="modal-content" style="display: none;">
                        <!-- Transaction Info -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Pabrik:</strong> <span id="detail-factory">-</span>
                            </div>
                            <div class="col-6 text-end">
                                <strong>Tanggal:</strong> <span id="detail-date">-</span>
                            </div>
                        </div>
                        
                        <!-- Items Table with Payment -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Paket</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">Dibayar</th>
                                        <th class="text-end">Sisa</th>
                                        <th class="text-center" style="width: 200px;">Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody id="detail-items"></tbody>
                                <tfoot class="table-secondary fw-bold">
                                    <tr>
                                        <td colspan="3" class="text-end">TOTAL:</td>
                                        <td class="text-end" id="detail-grand-total">0</td>
                                        <td class="text-end" id="detail-total-bayar">0</td>
                                        <td class="text-end" id="detail-total-hutang">0</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Global Lunas Button -->
                        <div class="mt-3" id="global-lunas-section">
                            <button type="button" class="btn btn-success btn-lg w-100" id="btn-lunas-all">
                                <i class="fas fa-check-double"></i> Lunaskan Semua Item
                            </button>
                        </div>
                        
                        <!-- Already Paid Message -->
                        <div class="alert alert-success align-items-center mt-3 d-none" id="already-paid-section">
                            <i class="fas fa-check-circle fa-2x me-3"></i>
                            <div><strong>LUNAS!</strong> Semua item sudah dibayar.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-outline-primary" id="btn-edit-transaksi">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.12.0/datatables.min.css" />
    <style>
        .payment-input { max-width: 100px; text-align: right; }
        .btn-pay-item, .btn-lunas-item { padding: 2px 8px; font-size: 12px; }
    </style>
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.12.0/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let currentTransaksiId = null;
        let currentData = null;
        
        const dataTable = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('transaksi-pembelians.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'tanggal_transaksi', name: 'tanggal_transaksi' },
                { data: 'factory_name', name: 'factory.name' },
                { data: 'total_items', name: 'total_items', orderable: false, searchable: false },
                { data: 'grand_total', name: 'grand_total' },
                { data: 'total_bayar', name: 'total_bayar', orderable: false, searchable: false },
                { data: 'total_hutang', name: 'total_hutang', orderable: false, searchable: false },
                { data: 'status_lunas', name: 'status_lunas' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
        });

        // Format angka dengan titik ribuan
        function formatNumber(num) {
            if (num === null || num === undefined || isNaN(num)) return '0';
            return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Parse angka dari format titik
        function parseNumber(str) {
            if (!str) return 0;
            return parseInt(str.toString().replace(/\./g, ''), 10) || 0;
        }

        // Open detail modal
        $(document).on('click', '.btn-view-detail', function(e) {
            e.preventDefault();
            currentTransaksiId = $(this).data('id');
            
            $('#modal-loading').show();
            $('#modal-content').hide();
            $('#detailModal').modal('show');
            
            loadTransaksiDetail();
        });

        function loadTransaksiDetail() {
            $.ajax({
                url: `/transaksi-pembelians/${currentTransaksiId}/detail`,
                method: 'GET',
                success: function(response) {
                    currentData = response;
                    displayDetail(response);
                },
                error: function() {
                    alert('Gagal memuat data.');
                    $('#detailModal').modal('hide');
                }
            });
        }

        function displayDetail(data) {
            $('#detail-factory').text(data.factory?.name || '-');
            $('#detail-date').text(data.tanggal_transaksi);
            $('#btn-edit-transaksi').attr('href', `/transaksi-pembelians/${data.id}/edit`);
            
            let itemsHtml = '';
            let grandTotal = 0, totalBayar = 0, totalHutang = 0;
            let allPaid = true;
            
            data.details.forEach(detail => {
                const total = detail.qty * detail.harga_per_unit;
                const dibayar = detail.total_bayar || 0;
                const sisa = total - dibayar;
                
                grandTotal += total;
                totalBayar += dibayar;
                totalHutang += sisa;
                
                if (sisa > 0) allPaid = false;
                
                const isPaid = sisa <= 0;
                
                itemsHtml += `
                    <tr data-detail-id="${detail.id}" data-sisa="${sisa}">
                        <td>${detail.paket?.name || '-'}</td>
                        <td class="text-center">${detail.qty}</td>
                        <td class="text-end">${formatNumber(detail.harga_per_unit)}</td>
                        <td class="text-end">${formatNumber(total)}</td>
                        <td class="text-end">${formatNumber(dibayar)}</td>
                        <td class="text-end ${sisa > 0 ? 'text-danger fw-bold' : 'text-success'}">${formatNumber(sisa)}</td>
                        <td class="text-center">
                            ${isPaid ? '<span class="badge bg-success">Lunas</span>' : `
                                <div class="d-flex gap-1 justify-content-center align-items-center">
                                    <input type="text" class="form-control form-control-sm payment-input" 
                                           data-id="${detail.id}" placeholder="Jumlah" value="">
                                    <button class="btn btn-success btn-sm btn-pay-item" data-id="${detail.id}" title="Bayar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm btn-lunas-item" data-id="${detail.id}" data-sisa="${sisa}" title="Lunas">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                </div>
                            `}
                        </td>
                    </tr>
                `;
            });
            
            $('#detail-items').html(itemsHtml);
            $('#detail-grand-total').text(formatNumber(grandTotal));
            $('#detail-total-bayar').text(formatNumber(totalBayar));
            $('#detail-total-hutang').text(formatNumber(totalHutang)).toggleClass('text-danger', totalHutang > 0);
            
            // Show/hide sections based on payment status
            if (allPaid) {
                $('#global-lunas-section').addClass('d-none');
                $('#already-paid-section').removeClass('d-none').addClass('d-flex');
            } else {
                $('#global-lunas-section').removeClass('d-none');
                $('#already-paid-section').removeClass('d-flex').addClass('d-none');
            }
            
            $('#modal-loading').hide();
            $('#modal-content').show();
        }

        // Pay single item (partial)
        $(document).on('click', '.btn-pay-item', function() {
            const detailId = $(this).data('id');
            const input = $(`.payment-input[data-id="${detailId}"]`);
            const amount = parseNumber(input.val());
            const sisa = parseNumber($(this).closest('tr').data('sisa'));
            
            if (amount <= 0) {
                Swal.fire('Perhatian', 'Masukkan jumlah pembayaran.', 'warning');
                return;
            }
            if (amount > sisa) {
                Swal.fire('Perhatian', 'Jumlah melebihi sisa (max: ' + formatNumber(sisa) + ')', 'warning');
                return;
            }
            
            payItem(detailId, amount);
        });

        // Lunas single item
        $(document).on('click', '.btn-lunas-item', function() {
            const detailId = $(this).data('id');
            const sisa = $(this).data('sisa');
            
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Lunaskan item ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: 'Ya, Lunaskan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    payItem(detailId, sisa);
                }
            });
        });

        // Lunas all items
        $('#btn-lunas-all').on('click', function() {
            Swal.fire({
                title: 'Konfirmasi Lunas Semua',
                text: 'Lunaskan SEMUA item yang belum dibayar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: 'Ya, Lunaskan Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/transaksi-pembelians/${currentTransaksiId}/payment`,
                        method: 'POST',
                        data: { _token: '{{ csrf_token() }}', full_pay: true },
                        success: function(response) {
                            if (response.success) {
                                loadTransaksiDetail();
                                dataTable.ajax.reload(null, false);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Semua item telah dilunaskan.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON?.message || 'Gagal memproses.', 'error');
                        }
                    });
                }
            });
        });

        function payItem(detailId, amount) {
            $.ajax({
                url: `/transaksi-pembelians/${currentTransaksiId}/pay-item`,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', detail_id: detailId, amount: amount },
                success: function(response) {
                    if (response.success) {
                        loadTransaksiDetail();
                        dataTable.ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Pembayaran berhasil dicatat.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Error', response.message || 'Gagal.', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Gagal memproses.', 'error');
                }
            });
        }

        // Real-time format input as user types
        $(document).on('input', '.payment-input', function() {
            let val = $(this).val().replace(/\D/g, ''); // Remove non-digits
            if (val) {
                val = parseInt(val, 10);
                $(this).val(formatNumber(val));
            }
        });
    </script>
@endpush
