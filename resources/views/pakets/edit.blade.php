@extends('layouts.app')

@section('title', __('Edit Paket'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Edit Paket') }}</h3>
                    <p class="text-subtitle text-muted">
                        {{ __('Perbarui data paket.') }}
                    </p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pakets.index') }}">{{ __('Paket') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('pakets.update', $paket) }}" method="POST">
                                @csrf
                                @method('PUT')
                                @include('pakets.include.form')
                                <a href="{{ route('pakets.index') }}" class="btn btn-secondary">{{ __('Kembali') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Perbarui') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
