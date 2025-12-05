@extends('layouts.app')

@section('title', __('Edit Unit'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Edit Unit') }}</h3>
                    <p class="text-subtitle text-muted">
                        {{ __('Edit an existing unit.') }}
                    </p>
                </div>

                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('units.index') }}">{{ __('Units') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('units.update', $unit->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">{{ __('Name') }}</label>
                                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('Unit Name') }}" value="{{ old('name', $unit->name) }}" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="code" class="form-label">{{ __('Code') }}</label>
                                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" placeholder="{{ __('Unit Code (e.g. kg, pcs)') }}" value="{{ old('code', $unit->code) }}" required>
                                            @error('code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('units.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
