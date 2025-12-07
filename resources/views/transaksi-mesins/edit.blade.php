@extends('layouts.app')

@section('title', __('Edit Transaksi Mesin'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Edit Transaksi Mesin') }}</h3>
                    <p class="text-subtitle text-muted">{{ __('Perbarui transaksi mesin.') }}</p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transaksi-mesins.index') }}">{{ __('Transaksi Mesin') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('transaksi-mesins.update', $transaksiMesin) }}" method="POST">
                                @csrf
                                @method('PUT')
                                @include('transaksi-mesins.include.form')
                                <a href="{{ route('transaksi-mesins.index') }}" class="btn btn-secondary">{{ __('Kembali') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Perbarui') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
