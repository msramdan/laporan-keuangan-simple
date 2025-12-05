<div class="row mb-4">
    <div class="col-md-6">
        <div class="form-group">
            <label for="date">{{ __('Tanggal') }}</label>
            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror"
                value="{{ date('Y-m-d') }}" required />
            @error('date')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="buyer_name">{{ __('Nama Pembeli') }}</label>
            <input type="text" name="buyer_name" id="buyer_name" class="form-control @error('buyer_name') is-invalid @enderror"
                placeholder="{{ __('Nama Pembeli') }}" required />
            @error('buyer_name')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
</div>

<h4 class="mb-3">{{ __('Pilih Item untuk Dijual') }}</h4>
<div class="table-responsive mb-4">
    <table class="table table-bordered table-hover">
        <thead class="bg-light">
            <tr>
                <th width="5%">{{ __('Pilih') }}</th>
                <th>{{ __('Produk') }}</th>
                <th>{{ __('Pabrik') }}</th>
                <th>{{ __('Berat') }}</th>
                <th>{{ __('Harga Beli') }}</th>
                <th>{{ __('Harga Jual') }}</th>
                <th>{{ __('Piutang') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $index => $item)
                <tr>
                    <td class="text-center">
                        <div class="form-check d-flex justify-content-center">
                            <input type="checkbox" name="items[{{ $index }}][purchase_item_id]" value="{{ $item->id }}" class="form-check-input item-checkbox" data-index="{{ $index }}">
                        </div>
                    </td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->purchase->factory->name }}</td>
                    <td>{{ $item->weight }} {{ $item->product->unit }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>
                        <input type="number" name="items[{{ $index }}][selling_price]" class="form-control form-control-sm" placeholder="Masukkan Harga Jual" disabled id="price-{{ $index }}">
                    </td>
                    <td>
                        <input type="number" name="items[{{ $index }}][receivable_amount]" class="form-control form-control-sm" placeholder="Masukkan Piutang" disabled id="receivable-{{ $index }}" value="0">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('js')
<script>
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const index = this.dataset.index;
            const priceInput = document.getElementById(`price-${index}`);
            const receivableInput = document.getElementById(`receivable-${index}`);
            
            if (this.checked) {
                priceInput.disabled = false;
                priceInput.required = true;
                receivableInput.disabled = false;
                receivableInput.required = true;
            } else {
                priceInput.disabled = true;
                priceInput.required = false;
                priceInput.value = '';
                receivableInput.disabled = true;
                receivableInput.required = false;
                receivableInput.value = '0';
            }
        });
    });
</script>
@endpush
