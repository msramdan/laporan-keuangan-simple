<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group">
            <label for="code">{{ __('Kode Pabrik') }}</label>
            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror"
                value="{{ isset($factory) ? $factory->code : old('code') }}" placeholder="{{ __('Kode Pabrik') }}" required />
            @error('code')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{ __('Nama Pabrik') }}</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ isset($factory) ? $factory->name : old('name') }}" placeholder="{{ __('Nama Pabrik') }}" required />
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-12">
        <div class="form-group">
            <label for="keterangan">{{ __('Keterangan') }}</label>
            <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                rows="3" placeholder="{{ __('Keterangan (opsional)') }}">{{ isset($factory) ? $factory->keterangan : old('keterangan') }}</textarea>
            @error('keterangan')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
