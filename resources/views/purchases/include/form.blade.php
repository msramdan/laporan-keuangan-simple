<div class="row mb-4">
    <div class="col-md-6">
        <div class="form-group">
            <label for="date">{{ __('Date') }}</label>
            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror"
                value="{{ isset($purchase) ? $purchase->date->format('Y-m-d') : date('Y-m-d') }}" required />
            @error('date')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="factory_id">{{ __('Factory') }}</label>
            <select name="factory_id" id="factory_id" class="form-control @error('factory_id') is-invalid @enderror" required>
                <option value="" disabled selected>{{ __('Select Factory') }}</option>
                @foreach ($factories as $factory)
                    <option value="{{ $factory->id }}" {{ (isset($purchase) && $purchase->factory_id == $factory->id) ? 'selected' : '' }}>
                        {{ $factory->name }}
                    </option>
                @endforeach
            </select>
            @error('factory_id')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">{{ __('Items') }}</h4>
    <button type="button" onclick="addItem()" class="btn btn-success btn-sm">
        <i class="fas fa-plus"></i> {{ __('Add Item') }}
    </button>
</div>
<div id="items-container">
    <!-- Items will be added here via JS -->
</div>

@push('js')
<script>
    let itemIndex = 0;
    const products = @json($products);
    const existingItems = @json(isset($purchase) ? $purchase->items : []);

    function addItem(data = null) {
        const container = document.getElementById('items-container');
        const isChecked = data ? (data.is_printable ? 'checked' : '') : 'checked';
        const itemHtml = `
            <div class="card mb-3 border item-row" id="item-${itemIndex}">
                <div class="card-body relative">
                    <button type="button" onclick="removeItem(${itemIndex})" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">X</button>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label text-sm font-bold">{{ __('Product') }}</label>
                                <select name="items[${itemIndex}][product_id]" class="form-control form-select" required>
                                    ${products.map(p => `<option value="${p.id}" ${data && data.product_id == p.id ? 'selected' : ''}>${p.name} (${p.unit})</option>`).join('')}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label text-sm font-bold">{{ __('Weight') }}</label>
                                <input type="number" step="0.01" name="items[${itemIndex}][weight]" class="form-control" value="${data ? data.weight : ''}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label text-sm font-bold">{{ __('Rejected Weight') }}</label>
                                <input type="number" step="0.01" name="items[${itemIndex}][rejected_weight]" class="form-control" value="${data ? data.rejected_weight : '0'}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label text-sm font-bold">{{ __('Total Price (Harga Beli)') }}</label>
                                <input type="number" name="items[${itemIndex}][price]" class="form-control" value="${data ? data.price : ''}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label text-sm font-bold">{{ __('Debt Amount (Hutang)') }}</label>
                                <input type="number" name="items[${itemIndex}][debt_amount]" class="form-control" value="${data ? data.debt_amount : '0'}" required>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-center mt-4">
                            <div class="form-check">
                                <input type="checkbox" name="items[${itemIndex}][is_printable]" value="1" class="form-check-input" ${isChecked}>
                                <label class="form-check-label">{{ __('Cetak (Printable)') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', itemHtml);
        itemIndex++;
    }

    function removeItem(index) {
        const item = document.getElementById(`item-${index}`);
        item.remove();
    }

    // Load existing items or add initial item
    document.addEventListener('DOMContentLoaded', () => {
        if (existingItems.length > 0) {
            existingItems.forEach(item => addItem(item));
        } else {
            addItem();
        }
    });
</script>
@endpush
