@extends('layouts.app')

@section('title', __('Transaksi Mesin'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Transaksi Mesin') }}</h3>
                    <p class="text-subtitle text-muted">{{ __('Daftar transaksi mesin.') }}</p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Transaksi Mesin') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <x-alert></x-alert>

            <div class="d-flex justify-content-end">
                <a href="{{ route('transaksi-mesins.create') }}" class="btn btn-primary mb-3">
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
                                            <th>{{ __('Client') }}</th>
                                            <th>{{ __('Mesin') }}</th>
                                            <th>{{ __('Banyak TSG') }}</th>
                                            <th>{{ __('TSG Tertolak') }}</th>
                                            <th>{{ __('Produk') }}</th>
                                            <th>{{ __('Harga Pabrik') }}</th>
                                            <th>{{ __('Harga Jual') }}</th>
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

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="detailModalLabel">
                        <i class="fas fa-cogs"></i> Detail Transaksi Mesin
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modal-loading" class="text-center py-4">
                        <div class="spinner-border text-info" role="status"></div>
                        <p class="mt-2">Memuat data...</p>
                    </div>
                    <div id="modal-content" style="display: none;">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td width="35%"><strong>Tanggal</strong></td>
                                <td>: <span id="detail-date">-</span></td>
                            </tr>
                            <tr>
                                <td><strong>Client</strong></td>
                                <td>: <span id="detail-client">-</span></td>
                            </tr>
                            <tr>
                                <td><strong>Mesin</strong></td>
                                <td>: <span id="detail-mesin">-</span></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Produk</strong></td>
                                <td>: <span id="detail-produk">-</span></td>
                            </tr>
                            <tr>
                                <td><strong>Banyak TSG (Kg)</strong></td>
                                <td>: <span id="detail-tsg" class="fw-bold">-</span></td>
                            </tr>
                            <tr>
                                <td><strong>TSG Tertolak (Kg)</strong></td>
                                <td>: <span id="detail-tsg-tertolak" class="text-danger">-</span></td>
                            </tr>
                            <tr>
                                <td><strong>Harga Pabrik</strong></td>
                                <td>: <span id="detail-harga-pabrik" class="text-primary fw-bold">-</span></td>
                            </tr>
                            <tr>
                                <td><strong>Harga Jual</strong></td>
                                <td>: <span id="detail-harga-jual" class="text-success fw-bold">-</span></td>
                            </tr>
                            <tr>
                                <td><strong>Margin/Profit</strong></td>
                                <td>: <span id="detail-margin" class="fw-bold">-</span></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>: <span id="detail-status">-</span></td>
                            </tr>
                        </table>

                        <!-- Lunas Button Section -->
                        <div class="mt-4" id="lunas-section">
                            <button type="button" class="btn btn-success btn-lg w-100" id="btn-lunas">
                                <i class="fas fa-check-double"></i> Tandai Lunas
                            </button>
                        </div>

                        <div class="alert alert-success align-items-center mt-4 d-none" id="already-paid-section">
                            <i class="fas fa-check-circle fa-2x me-3"></i>
                            <div><strong>LUNAS!</strong> Transaksi ini sudah dibayar.</div>
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
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.12.0/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let currentTransaksiId = null;
        
        const dataTable = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('transaksi-mesins.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'tanggal_transaksi', name: 'tanggal_transaksi' },
                { data: 'client_name', name: 'client.name' },
                { data: 'mesin_name', name: 'mesin.name' },
                { data: 'banyak_tsg', name: 'banyak_tsg' },
                { data: 'banyak_tsg_tertolak', name: 'banyak_tsg_tertolak' },
                { data: 'nama_produk', name: 'nama_produk' },
                { data: 'harga_pabrik', name: 'harga_pabrik' },
                { data: 'harga_jual', name: 'harga_jual' },
                { data: 'status_lunas', name: 'status_lunas' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
        });

        // Format number with thousand separators
        function formatNumber(num) {
            if (num === null || num === undefined || isNaN(num)) return '0';
            return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        
        function formatDecimal(num, decimals = 2) {
            if (num === null || num === undefined || isNaN(num)) return '0';
            // Remove unnecessary decimals if the number is whole
            if (Math.floor(num) == num) {
                return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            return parseFloat(num).toFixed(decimals).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Open detail modal
        $(document).on('click', '.btn-view-detail', function(e) {
            e.preventDefault();
            currentTransaksiId = $(this).data('id');
            
            $('#modal-loading').show();
            $('#modal-content').hide();
            $('#detailModal').modal('show');
            
            $.ajax({
                url: `/transaksi-mesins/${currentTransaksiId}/detail`,
                method: 'GET',
                success: function(response) {
                    displayDetail(response);
                },
                error: function() {
                    Swal.fire('Error', 'Gagal memuat data transaksi.', 'error');
                    $('#detailModal').modal('hide');
                }
            });
        });

        function displayDetail(data) {
            $('#detail-date').text(data.tanggal_transaksi);
            $('#detail-client').text(data.client?.name || '-');
            $('#detail-mesin').text(data.mesin?.name || '-');
            $('#detail-produk').text(data.nama_produk || '-');
            $('#detail-tsg').text(formatDecimal(data.banyak_tsg) + ' Kg');
            $('#detail-tsg-tertolak').text(formatDecimal(data.banyak_tsg_tertolak) + ' Kg');
            $('#detail-harga-pabrik').text('Rp ' + formatNumber(data.harga_pabrik));
            $('#detail-harga-jual').text('Rp ' + formatNumber(data.harga_jual));
            
            const margin = data.harga_jual - data.harga_pabrik;
            $('#detail-margin')
                .text('Rp ' + formatNumber(margin))
                .removeClass('text-danger text-success')
                .addClass(margin >= 0 ? 'text-success' : 'text-danger');
            
            $('#detail-status').html(data.status_lunas 
                ? '<span class="badge bg-success">Lunas</span>' 
                : '<span class="badge bg-warning">Belum Lunas</span>');
            
            $('#btn-edit-transaksi').attr('href', `/transaksi-mesins/${data.id}/edit`);
            
            // Show/hide based on payment status
            if (data.status_lunas) {
                $('#lunas-section').addClass('d-none');
                $('#already-paid-section').removeClass('d-none').addClass('d-flex');
            } else {
                $('#lunas-section').removeClass('d-none');
                $('#already-paid-section').removeClass('d-flex').addClass('d-none');
            }
            
            $('#modal-loading').hide();
            $('#modal-content').show();
        }

        // Lunas button with SweetAlert
        $('#btn-lunas').on('click', function() {
            Swal.fire({
                title: 'Konfirmasi Lunas',
                text: 'Yakin ingin menandai transaksi ini sebagai LUNAS?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tandai Lunas!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/transaksi-mesins/${currentTransaksiId}/payment`,
                        method: 'POST',
                        data: { _token: '{{ csrf_token() }}', status_lunas: true },
                        success: function(response) {
                            if (response.success) {
                                // Update UI without closing modal
                                $('#lunas-section').addClass('d-none');
                                $('#already-paid-section').removeClass('d-none').addClass('d-flex');
                                $('#detail-status').html('<span class="badge bg-success">Lunas</span>');
                                dataTable.ajax.reload(null, false);
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Transaksi telah ditandai lunas.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                        }
                    });
                }
            });
        });
    </script>
@endpush
