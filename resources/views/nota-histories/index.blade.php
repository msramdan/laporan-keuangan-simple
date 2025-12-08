@extends('layouts.app')

@section('title', __('Riwayat Nota'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Riwayat Nota') }}</h3>
                    <p class="text-subtitle text-muted">{{ __('Daftar nota yang pernah dicetak.') }}</p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Riwayat Nota') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <x-alert></x-alert>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive p-1">
                        <table class="table table-striped" id="data-table" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('No') }}</th>
                                    <th>{{ __('Tanggal Cetak') }}</th>
                                    <th>{{ __('Tipe Nota') }}</th>
                                    <th>{{ __('Dicetak Oleh') }}</th>
                                    <th>{{ __('Aksi') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.12.0/datatables.min.css" />
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.12.0/datatables.min.js"></script>
    <script>
        $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('nota-histories.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'nota_type', name: 'nota_type' },
                { data: 'creator_name', name: 'creator.name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
        });
    </script>
@endpush
