@extends('layouts.app')

@section('title', __('Edit Client'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Edit Client') }}</h3>
                    <p class="text-subtitle text-muted">{{ __('Perbarui data client.') }}</p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">{{ __('Client') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('clients.update', $client) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('clients.include.form')
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">{{ __('Kembali') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('Perbarui') }}</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
