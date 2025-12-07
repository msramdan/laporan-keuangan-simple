<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group">
            <label for="code">{{ __('Kode Mesin') }}</label>
            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror"
                value="{{ isset($mesin) ? $mesin->code : old('code') }}" placeholder="{{ __('Kode Mesin') }}" required />
            @error('code')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{ __('Nama Mesin') }}</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ isset($mesin) ? $mesin->name : old('name') }}" placeholder="{{ __('Nama Mesin') }}" required />
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
                rows="3" placeholder="{{ __('Keterangan (opsional)') }}">{{ isset($mesin) ? $mesin->keterangan : old('keterangan') }}</textarea>
            @error('keterangan')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
