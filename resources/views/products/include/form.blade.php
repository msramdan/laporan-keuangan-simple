<div class="row mb-2">
    <div class="col-md-4">
        <div class="form-group">
            <label for="name">{{ __('Nama Produk') }}</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ isset($product) ? $product->name : old('name') }}" placeholder="{{ __('Nama Produk') }}" required />
            @error('name')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="unit">{{ __('Satuan') }}</label>
            <select name="unit" id="unit" class="form-select @error('unit') is-invalid @enderror" required>
                <option value="" disabled {{ !isset($product) && !old('unit') ? 'selected' : '' }}>{{ __('Pilih Satuan') }}</option>
                @foreach ($units as $unitItem)
                    <option value="{{ $unitItem->code }}" {{ (isset($product) && $product->unit == $unitItem->code) || old('unit') == $unitItem->code ? 'selected' : '' }}>
                        {{ $unitItem->name }} ({{ $unitItem->code }})
                    </option>
                @endforeach
            </select>
            @error('unit')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="code">{{ __('Kode') }}</label>
            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror"
                value="{{ isset($product) ? $product->code : old('code') }}" placeholder="{{ __('Kode') }}" required />
            @error('code')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
</div>
