@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary p-2 rounded-circle me-3 text-white">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Edit Sale</h5>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('sales.update', $sale) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Client Information --}}
                        <div class="card mb-4 border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>Client Information
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Client Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="fas fa-user-tag text-muted"></i>
                                            </span>
                                            <input type="text" name="client_name" class="form-control"
                                                   value="{{ old('client_name', $sale->client_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Client Phone</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="fas fa-phone text-muted"></i>
                                            </span>
                                            <input type="text" name="client_phone" class="form-control"
                                                   value="{{ old('client_phone', $sale->client_phone) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Vendor & Product --}}
                        <div class="card mb-4 border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-store me-2"></i>Vendor & Product
                                </h6>

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Select Existing Vendor</label>
                                    <select id="vendorSelect" name="vendor_id" class="form-select">
                                        <option value="">-- Select Existing Vendor --</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}"
                                                {{ old('vendor_id', $sale->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                                {{ $vendor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Or Enter New Vendor</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="fas fa-store-alt text-muted"></i>
                                        </span>
                                        <input type="text" id="newVendorInput" name="new_vendor" class="form-control"
                                               value="{{ old('new_vendor') }}" placeholder="Enter vendor name if not listed above">
                                    </div>
                                </div>

                                <div id="newProductDiv" class="{{ old('new_vendor') ? '' : 'd-none' }}">
                                    <label class="form-label fw-medium">New Product</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="fas fa-box text-muted"></i>
                                        </span>
                                        <input type="text" name="new_product" class="form-control"
                                               value="{{ old('new_product') }}" placeholder="Enter product name for new vendor">
                                    </div>
                                </div>

                                <div id="productSelectDiv" class="{{ old('new_vendor') ? 'd-none' : '' }}">
                                    <label class="form-label fw-medium">Select Product</label>
                                    <select id="productSelect" name="product_id" class="form-select">
                                        <option value="">-- Select a Vendor First --</option>
                                        {{-- Products loaded dynamically by JS --}}
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Financial Details --}}
                        <div class="card mb-4 border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-dollar-sign me-2"></i>Financial Details
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium">Quantity</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="fas fa-sort-numeric-up"></i>
                                            </span>
                                            <input type="number" name="quantity" class="form-control"
                                                   value="{{ old('quantity', $sale->quantity) }}" min="1" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-medium">Vendor Price (per unit)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">$</span>
                                            <input type="number" step="0.01" name="vendor_price" class="form-control"
                                                   value="{{ old('vendor_price', $sale->vendor_price / $sale->quantity) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-medium">Additional Expenses</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">$</span>
                                            <input type="number" step="0.01" name="expenses" class="form-control"
                                                   value="{{ old('expenses', $sale->expenses) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <label class="form-label fw-medium">Sale Price (per unit)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">$</span>
                                            <input type="number" step="0.01" name="sale_price" class="form-control"
                                                   value="{{ old('sale_price', $sale->sale_price / $sale->quantity) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-8 mt-3">
                                        <label class="form-label fw-medium">Comment</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="fas fa-comment"></i>
                                            </span>
                                            <textarea name="comment" class="form-control" rows="1">{{ old('comment', $sale->comment) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 pt-3 border-top">
                                    <div class="row">
                                        <div class="col-md-8 mb-3 mb-md-0">
                                            <div class="form-text mb-2">
                                                <strong>Profit Calculator:</strong> Sale Price - (Vendor Price + Expenses)
                                            </div>
                                            <div class="bg-white p-2 rounded border">
                                                <span id="profitDisplay" class="fw-bold">$0.00</span>
                                                <span id="marginDisplay" class="small text-muted ms-2">(0% margin)</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-md-end">
                                            <button type="button" id="calculateBtn" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-calculator me-1"></i> Calculate Profit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Sale
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JS for dynamic products & profit calculation --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let vendorSelect = document.getElementById('vendorSelect');
    let newVendorInput = document.getElementById('newVendorInput');
    let productSelectDiv = document.getElementById('productSelectDiv');
    let newProductDiv = document.getElementById('newProductDiv');
    let productSelect = document.getElementById('productSelect');

    let calculateBtn = document.getElementById('calculateBtn');
    let vendorPriceInput = document.querySelector('input[name="vendor_price"]');
    let expensesInput = document.querySelector('input[name="expenses"]');
    let salePriceInput = document.querySelector('input[name="sale_price"]');
    let quantityInput = document.querySelector('input[name="quantity"]');
    let profitDisplay = document.getElementById('profitDisplay');
    let marginDisplay = document.getElementById('marginDisplay');

    function calculateProfit() {
        const vendorPrice = parseFloat(vendorPriceInput.value || 0) * parseFloat(quantityInput.value || 1);
        const expenses = parseFloat(expensesInput.value || 0);
        const salePrice = parseFloat(salePriceInput.value || 0) * parseFloat(quantityInput.value || 1);

        const profit = salePrice - (vendorPrice + expenses);
        const marginPercent = salePrice > 0 ? (profit / salePrice * 100).toFixed(1) : 0;

        profitDisplay.textContent = '$' + profit.toFixed(2);
        marginDisplay.textContent = `(${marginPercent}% margin)`;

        if (profit > 0) {
            profitDisplay.classList.remove('text-danger');
            profitDisplay.classList.add('text-success');
        } else {
            profitDisplay.classList.remove('text-success');
            profitDisplay.classList.add('text-danger');
        }
    }

    calculateBtn.addEventListener('click', calculateProfit);
    vendorPriceInput.addEventListener('input', calculateProfit);
    expensesInput.addEventListener('input', calculateProfit);
    salePriceInput.addEventListener('input', calculateProfit);
    quantityInput.addEventListener('input', calculateProfit);

    // Load products dynamically for selected vendor
    function loadProducts(vendorId, selectedProductId = null) {
        if (!vendorId) return;
        fetch(`/vendors/${vendorId}/products`)
            .then(res => res.json())
            .then(data => {
                productSelect.innerHTML = '<option value="">-- Select Product --</option>';
                data.forEach(product => {
                    const selected = selectedProductId == product.id ? 'selected' : '';
                    productSelect.innerHTML += `<option value="${product.id}" ${selected}>${product.name}</option>`;
                });
            });
    }

    // On page load, load products for current vendor
    @if($sale->vendor_id)
        loadProducts({{ $sale->vendor_id }}, {{ $sale->product_id }});
        productSelectDiv.classList.remove('d-none');
    @endif

    vendorSelect.addEventListener('change', function() {
        if (this.value) {
            newVendorInput.value = '';
            newProductDiv.classList.add('d-none');
            productSelectDiv.classList.remove('d-none');
            loadProducts(this.value);
        } else {
            productSelect.innerHTML = '<option value="">-- Select a Vendor First --</option>';
        }
    });

    newVendorInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            vendorSelect.value = "";
            productSelectDiv.classList.add('d-none');
            newProductDiv.classList.remove('d-none');
        } else {
            productSelectDiv.classList.remove('d-none');
            newProductDiv.classList.add('d-none');
        }
    });
});
</script>
@endsection
