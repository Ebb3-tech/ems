@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary p-2 rounded-circle me-3 text-white">
                            <i class="fas fa-plus"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Create New Sale</h5>
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

                    <form action="{{ route('sales.store') }}" method="POST">
                        @csrf
                        
                        <div class="card mb-4 border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>Client Information
                                </h6>
                                
                                <div class="col-md-6">
    <label class="form-label fw-medium">Client Name</label>
    <div class="input-group">
        <span class="input-group-text bg-white">
            <i class="fas fa-user-tag text-muted"></i>
        </span>
        <input type="text" id="clientNameInput" name="client_name" class="form-control"
               value="{{ old('client_name') }}" placeholder="Start typing client name..." required>
    </div>
</div>

<div class="col-md-6">
    <label class="form-label fw-medium">Client Phone</label>
    <div class="input-group">
        <span class="input-group-text bg-white">
            <i class="fas fa-phone text-muted"></i>
        </span>
        <input type="text" id="clientPhoneInput" name="client_phone" class="form-control"
               value="{{ old('client_phone') }}" required>
    </div>
</div>

                            </div>
                        </div>
                        
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
                                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                                {{ $vendor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Choose an existing vendor or add a new one below</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Or Enter New Vendor</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="fas fa-store-alt text-muted"></i>
                                        </span>
                                        <input type="text" id="newVendorInput" name="new_vendor" class="form-control"
                                               value="{{ old('new_vendor') }}"
                                               placeholder="Enter vendor name if not listed above">
                                    </div>
                                </div>
                                
                                <div id="newProductDiv" class="{{ old('new_vendor') ? '' : 'd-none' }}">
                                    <label class="form-label fw-medium">New Product</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="fas fa-box text-muted"></i>
                                        </span>
                                        <input type="text" name="new_product" class="form-control"
                                               value="{{ old('new_product') }}"
                                               placeholder="Enter product name for this new vendor">
                                    </div>
                                    <div class="form-text">Required if adding a new vendor</div>
                                </div>
                                
                                <div id="productSelectDiv" class="{{ old('new_vendor') ? 'd-none' : '' }}">
                                    <label class="form-label fw-medium">Select Product</label>
                                    <select id="productSelect" name="product_id" class="form-select">
                                        <option value="">-- Select a Vendor First --</option>
                                        <!-- Products will be loaded dynamically -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                       <div class="card mb-4 border-0 bg-light">
    <div class="card-body">
        <h6 class="card-title text-primary mb-3">
            <i class="fas fa-dollar-sign me-2"></i>Financial Details
        </h6>

        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-medium">Quantity</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-sort-numeric-up"></i>
                    </span>
                    <input type="number" id="quantityInput" name="quantity" class="form-control" 
                        value="{{ old('quantity', 1) }}" min="1" required>
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-medium">Vendor Price (per item)</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">Frw</span>
                    <input type="number" step="0.01" id="vendorPriceInput" name="vendor_price" class="form-control" 
                        value="{{ old('vendor_price') }}" required>
                </div>
                <div class="form-text">Paid per item to vendor</div>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-medium">Additional Expenses</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">Frw</span>
                    <input type="number" step="0.01" id="expensesInput" name="expenses" class="form-control"
                        value="{{ old('expenses', '0.00') }}">
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-medium">Sale Price (per item)</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">Frw</span>
                    <input type="number" step="0.01" id="salePriceInput" name="sale_price" class="form-control"
                        value="{{ old('sale_price') }}" required>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <label class="form-label fw-medium">Comment</label>
            <div class="input-group">
                <span class="input-group-text bg-white">
                    <i class="fas fa-comment"></i>
                </span>
                <textarea name="comment" class="form-control" rows="1"
                    placeholder="Optional notes about this sale">{{ old('comment') }}</textarea>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top">
            <div class="form-text mb-2">
                <strong>Totals & Profit:</strong> (Sale × Qty) - ((Vendor × Qty) + Expenses)
            </div>
            <div class="bg-white p-3 rounded border">
                <div class="d-flex justify-content-between">
                    <div>Total Vendor Cost: <strong id="totalVendor">Frw0</strong></div>
                    <div>Total Sale: <strong id="totalSale">Frw0</strong></div>
                </div>
                <div class="mt-2">
                    Profit: <strong id="profitDisplay">Frw0</strong>
                    <span id="marginDisplay" class="small text-muted ms-2">(0% margin)</span>
                </div>
            </div>
        </div>
    </div>
</div>

                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Sale
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    let vendorSelect = document.getElementById('vendorSelect');
    let newVendorInput = document.getElementById('newVendorInput');
    let productSelectDiv = document.getElementById('productSelectDiv');
    let newProductDiv = document.getElementById('newProductDiv');
    let productSelect = document.getElementById('productSelect');

    // Financial inputs
    const quantityInput = document.getElementById('quantityInput');
    const vendorPriceInput = document.getElementById('vendorPriceInput');
    const expensesInput = document.getElementById('expensesInput');
    const salePriceInput = document.getElementById('salePriceInput');

    const totalVendor = document.getElementById('totalVendor');
    const totalSale = document.getElementById('totalSale');
    const profitDisplay = document.getElementById('profitDisplay');
    const marginDisplay = document.getElementById('marginDisplay');

    function calculateProfit() {
        const qty = parseFloat(quantityInput.value) || 0;
        const vendorPrice = parseFloat(vendorPriceInput.value) || 0;
        const salePrice = parseFloat(salePriceInput.value) || 0;
        const expenses = parseFloat(expensesInput.value) || 0;

        const totalVendorCost = vendorPrice * qty;
        const totalSaleAmount = salePrice * qty;
        const profit = totalSaleAmount - (totalVendorCost + expenses);
        const marginPercent = totalSaleAmount > 0 ? (profit / totalSaleAmount * 100).toFixed(1) : 0;

        totalVendor.textContent = 'Frw' + totalVendorCost.toFixed(2);
        totalSale.textContent = 'Frw' + totalSaleAmount.toFixed(2);
        profitDisplay.textContent = 'Frw' + profit.toFixed(2);
        marginDisplay.textContent = `(${marginPercent}% margin)`;

        profitDisplay.classList.toggle('text-success', profit > 0);
        profitDisplay.classList.toggle('text-danger', profit <= 0);
    }

    [quantityInput, vendorPriceInput, expensesInput, salePriceInput].forEach(input => {
        input.addEventListener('input', calculateProfit);
    });

    calculateProfit(); // initial call

    // --- vendor/product dynamic load (unchanged from your code) ---
    vendorSelect.addEventListener('change', function() {
        if (this.value) {
            newVendorInput.value = '';
            newProductDiv.classList.add('d-none');
            productSelectDiv.classList.remove('d-none');
            productSelect.innerHTML = '<option>Loading...</option>';

            fetch(`/vendors/${this.value}/products`)
                .then(res => res.json())
                .then(data => {
                    productSelect.innerHTML = '<option value="">-- Select Product --</option>';
                    data.forEach(product => {
                        productSelect.innerHTML += `<option value="${product.id}">${product.name}</option>`;
                    });
                });
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

    if (vendorSelect.value) {
        fetch(`/vendors/${vendorSelect.value}/products`)
            .then(r => r.json())
            .then(data => {
                productSelect.innerHTML = '<option value="">-- Select Product --</option>';
                const oldProductId = "{{ old('product_id') }}";
                data.forEach(product => {
                    const selected = product.id == oldProductId ? 'selected' : '';
                    productSelect.innerHTML += `<option value="${product.id}" ${selected}>${product.name}</option>`;
                });
            });
    }
});

// Client autocomplete
const clientNameInput = document.getElementById('clientNameInput');
const clientPhoneInput = document.getElementById('clientPhoneInput');

let debounceTimer;

clientNameInput.addEventListener('input', function() {
    clearTimeout(debounceTimer);
    const term = this.value.trim();

    if (term.length < 2) return; // wait for at least 2 chars

    debounceTimer = setTimeout(() => {
        fetch(`/clients/search?term=${encodeURIComponent(term)}`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    // pick the first matching client
                    clientNameInput.value = data[0].client_name;
                    clientPhoneInput.value = data[0].client_phone;
                }
            });
    }, 300); // small debounce
});

</script>

@endsection