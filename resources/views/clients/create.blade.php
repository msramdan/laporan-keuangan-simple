@extends('layouts.app')

@section('title', __('Tambah Client'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Tambah Client') }}</h3>
                    <p class="text-subtitle text-muted">{{ __('Buat client baru.') }}</p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">{{ __('Client') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Tambah') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf
                        @include('clients.include.form')
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">{{ __('Kembali') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
