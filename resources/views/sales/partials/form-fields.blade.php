{{-- resources/views/sales/partials/form-fields.blade.php --}}
<div class="card mb-4 border-0 bg-light">
    <div class="card-body">
        <h6 class="card-title text-primary mb-3">Client Information</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-medium">Client Name</label>
                <input type="text" name="client_name" class="form-control" value="{{ old('client_name', $sale->client_name ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium">Client Phone</label>
                <input type="text" name="client_phone" class="form-control" value="{{ old('client_phone', $sale->client_phone ?? '') }}" required>
            </div>
        </div>
    </div>
</div>

{{-- Continue with vendor/product selection, quantity, prices, etc. --}}
