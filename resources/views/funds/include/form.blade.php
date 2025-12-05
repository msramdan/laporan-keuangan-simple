<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group">
            <label for="date">{{ __('Tanggal') }}</label>
            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror"
                value="{{ isset($fund) ? $fund->date : old('date') }}" required />
            @error('date')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="type">{{ __('Tipe') }}</label>
            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                <option value="" disabled selected>{{ __('Pilih Tipe') }}</option>
                <option value="IN" {{ (isset($fund) && $fund->type == 'IN') || old('type') == 'IN' ? 'selected' : '' }}>{{ __('Pemasukan') }}</option>
                <option value="OUT" {{ (isset($fund) && $fund->type == 'OUT') || old('type') == 'OUT' ? 'selected' : '' }}>{{ __('Pengeluaran') }}</option>
            </select>
            @error('type')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="amount">{{ __('Jumlah') }}</label>
            <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror"
                value="{{ isset($fund) ? $fund->amount : old('amount') }}" placeholder="{{ __('Jumlah') }}" required />
            @error('amount')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="description">{{ __('Keterangan') }}</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                placeholder="{{ __('Keterangan') }}">{{ isset($fund) ? $fund->description : old('description') }}</textarea>
            @error('description')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
</div>
