<div class="row mb-3">
    <div class="col-md-4">
        <div class="form-group">
            <label for="client_id">{{ __('Client') }} <span class="text-danger">*</span></label>
            <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                <option value="">-- Pilih Client --</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ (isset($transaksiMesin) ? $transaksiMesin->client_id : old('client_id')) == $client->id ? 'selected' : '' }}>
                        {{ $client->code }} - {{ $client->name }}
                    </option>
                @endforeach
            </select>
            @error('client_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="mesin_id">{{ __('Mesin') }} <span class="text-danger">*</span></label>
            <select name="mesin_id" id="mesin_id" class="form-select @error('mesin_id') is-invalid @enderror" required>
                <option value="">-- Pilih Mesin --</option>
                @foreach($mesins as $mesin)
                    <option value="{{ $mesin->id }}" {{ (isset($transaksiMesin) ? $transaksiMesin->mesin_id : old('mesin_id')) == $mesin->id ? 'selected' : '' }}>
                        {{ $mesin->code }} - {{ $mesin->name }}
                    </option>
                @endforeach
            </select>
            @error('mesin_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="tanggal_transaksi">{{ __('Tanggal Transaksi') }} <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" 
                class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                value="{{ isset($transaksiMesin) ? $transaksiMesin->tanggal_transaksi->format('Y-m-d') : old('tanggal_transaksi', date('Y-m-d')) }}" required />
            @error('tanggal_transaksi')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <div class="form-group">
            <label for="nama_produk">{{ __('Nama Produk') }} <span class="text-danger">*</span></label>
            <input type="text" name="nama_produk" id="nama_produk" 
                class="form-control @error('nama_produk') is-invalid @enderror"
                value="{{ isset($transaksiMesin) ? $transaksiMesin->nama_produk : old('nama_produk') }}" 
                placeholder="{{ __('Nama Produk') }}" required />
            @error('nama_produk')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <div class="form-group">
            <label for="banyak_tsg">{{ __('Banyak TSG (Kg)') }} <span class="text-danger">*</span></label>
            <input type="text" name="banyak_tsg" id="banyak_tsg" data-format="number"
                class="form-control @error('banyak_tsg') is-invalid @enderror"
                value="{{ isset($transaksiMesin) ? $transaksiMesin->banyak_tsg : old('banyak_tsg', 0) }}" 
                placeholder="0" required />
            @error('banyak_tsg')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="banyak_tsg_tertolak">{{ __('Banyak TSG Tertolak (Kg)') }}</label>
            <input type="text" name="banyak_tsg_tertolak" id="banyak_tsg_tertolak" data-format="number"
                class="form-control @error('banyak_tsg_tertolak') is-invalid @enderror"
                value="{{ isset($transaksiMesin) ? $transaksiMesin->banyak_tsg_tertolak : old('banyak_tsg_tertolak', 0) }}" 
                placeholder="0" />
            @error('banyak_tsg_tertolak')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <div class="form-group">
            <label for="harga_pabrik">{{ __('Harga Pabrik') }} <span class="text-danger">*</span></label>
            <input type="text" name="harga_pabrik" id="harga_pabrik" data-format="number"
                class="form-control @error('harga_pabrik') is-invalid @enderror"
                value="{{ isset($transaksiMesin) ? $transaksiMesin->harga_pabrik : old('harga_pabrik', 0) }}" 
                required />
            @error('harga_pabrik')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="harga_jual">{{ __('Harga Jual') }} <span class="text-danger">*</span></label>
            <input type="text" name="harga_jual" id="harga_jual" data-format="number"
                class="form-control @error('harga_jual') is-invalid @enderror"
                value="{{ isset($transaksiMesin) ? $transaksiMesin->harga_jual : old('harga_jual', 0) }}" 
                required />
            @error('harga_jual')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <div class="form-check">
            <input type="checkbox" name="status_lunas" id="status_lunas" class="form-check-input" value="1"
                {{ (isset($transaksiMesin) && $transaksiMesin->status_lunas) ? 'checked' : '' }} />
            <label for="status_lunas" class="form-check-label">{{ __('Sudah Lunas') }}</label>
        </div>
    </div>
</div>
