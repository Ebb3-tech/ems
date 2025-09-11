@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <!-- Header Section -->
        <div class="card-header bg-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <div class="bg-primary p-2 rounded me-3 text-white">
                        
                    </div>
                    <h2 class="h4 mb-0 fw-bold">Sales Overview</h2>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('sales.create') }}" 
                        class="btn btn-primary d-flex align-items-center">
                        <i class="fas fa-plus-circle me-2"></i>
                        Create New Sale
                    </a>
                    <button type="button" id="toggleFilters" class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="fas fa-filter me-2"></i>
                        Filter
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Summary Stats -->
        <div class="card-body bg-light border-bottom">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="text-muted small mb-1">Total Sales</div>
                            <div class="h4 fw-bold" id="total-sales-count">{{ $sales->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="text-muted small mb-1">Total Revenue</div>
                            <div class="h4 fw-bold text-success" id="total-revenue">Frw{{ number_format($sales->sum('sale_price'), 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="text-muted small mb-1">Total Expenses</div>
                            <div class="h4 fw-bold text-danger" id="total-expenses">Frw{{ number_format($sales->sum('expenses') + $sales->sum('vendor_price'), 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="text-muted small mb-1">Net Income</div>
                            <div class="h4 fw-bold text-primary" id="net-income">Frw{{ number_format($sales->sum('sale_price') - ($sales->sum('expenses') + $sales->sum('vendor_price')), 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="card-body border-bottom" id="filterSection" style="display: none;">
            <form id="filterForm" class="mb-0">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" id="searchInput" name="search" placeholder="Search by client or product..." class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="productFilter" name="product_id" class="form-select">
                            <option value="">All Products</option>
                            @foreach(App\Models\Product::orderBy('name')->get() as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="vendorFilter" name="vendor_id" class="form-select">
                            <option value="">All Vendors</option>
                            @foreach(App\Models\Vendor::orderBy('name')->get() as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white">Min </span>
                            <input type="number" id="minPrice" name="min_price" placeholder="Min price" class="form-control">
                            <span class="input-group-text bg-white">Max </span>
                            <input type="number" id="maxPrice" name="max_price" placeholder="Max price" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar text-muted"></i>
                            </span>
                            <input type="date" id="startDate" name="start_date" placeholder="Start date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar-alt text-muted"></i>
                            </span>
                            <input type="date" id="endDate" name="end_date" placeholder="End date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="button" id="resetFilters" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Tags -->
        <div class="d-flex flex-wrap gap-2 px-3 pt-3" id="filterTags" style="display: none !important;">
            <!-- Filter tags will be dynamically added here -->
        </div>

        <!-- Sales Table -->
        <div class="table-responsive">
    <table class="table table-hover table-striped mb-0">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Vendor</th>
                <th>Vendor Price</th>
                <th>Expenses</th>
                <th>Sale Price</th>
                <th>Income</th>
                <th>Comment</th> <!-- ✅ Fixed missing "<" -->
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody id="salesTableBody">
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="fw-medium">{{ $sale->client_name }}</div>
                        <div class="small text-muted">{{ $sale->client_phone }}</div>
                    </td>
                    <td>{{ $sale->product->name }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>{{ $sale->vendor->name ?? 'N/A' }}</td>
                    <td>Frw{{ number_format($sale->vendor_price, 2) }}</td>
                    <td>Frw{{ number_format($sale->expenses, 2) }}</td>
                    <td class="fw-medium">Frw{{ number_format($sale->sale_price, 2) }}</td>
                    <td class="fw-bold text-success">Frw{{ number_format($sale->income, 2) }}</td>
                    <td>{{ $sale->comment }}</td>
                    <td class="text-end">
    <div class="btn-group">
        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-sm btn-outline-primary" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
        <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-info" title="View">
            <i class="fas fa-eye"></i>
        </a>
        <form action="{{ route('sales.destroy', $sale) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this sale?');">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </div>
</td>

                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center py-5">
                        <div class="py-4">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <h5>No sales recorded yet</h5>
                            <p class="text-muted mb-4">Get started by creating your first sale record</p>
                            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i>
                                Create New Sale
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

        

    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterSection = document.getElementById('filterSection');
    const toggleFilters = document.getElementById('toggleFilters');
    const filterForm = document.getElementById('filterForm');
    const resetFilters = document.getElementById('resetFilters');
    const salesTableBody = document.getElementById('salesTableBody');
    const filterTags = document.getElementById('filterTags');
    
    // Statistics elements
    const totalSalesCount = document.getElementById('total-sales-count');
    const totalRevenue = document.getElementById('total-revenue');
    const totalExpenses = document.getElementById('total-expenses');
    const netIncome = document.getElementById('net-income');
    const resultsCount = document.getElementById('resultsCount');

    // Toggle filter section visibility
    toggleFilters.addEventListener('click', function() {
        if (filterSection.style.display === 'none') {
            filterSection.style.display = 'block';
            toggleFilters.innerHTML = '<i class="fas fa-times me-2"></i>Close Filters';
            toggleFilters.classList.replace('btn-outline-secondary', 'btn-secondary');
        } else {
            filterSection.style.display = 'none';
            toggleFilters.innerHTML = '<i class="fas fa-filter me-2"></i>Filter';
            toggleFilters.classList.replace('btn-secondary', 'btn-outline-secondary');
        }
    });

    // Reset filters
    resetFilters.addEventListener('click', function() {
        filterForm.reset();
        applyFilters();
    });

    // Handle form submission
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });

    // Handle filter application
    function applyFilters() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();
        
        let hasFilters = false;
        filterTags.innerHTML = '';
        
        for (let [key, value] of formData.entries()) {
            // Skip CSRF token
            if (key === '_token') continue;
            
            if (value) {
                params.append(key, value);
                hasFilters = true;
                
                // Create filter tag
                const tag = document.createElement('span');
                tag.className = 'badge bg-light text-dark py-2 px-3 me-2 mb-2';
                
                let labelText = '';
                switch(key) {
                    case 'search':
                        labelText = `Search: ${value}`;
                        break;
                    case 'product_id':
                        const productOption = document.querySelector(`#productFilter option[value="${value}"]`);
                        labelText = `Product: ${productOption ? productOption.textContent : value}`;
                        break;
                    case 'vendor_id':
                        const vendorOption = document.querySelector(`#vendorFilter option[value="${value}"]`);
                        labelText = `Vendor: ${vendorOption ? vendorOption.textContent : value}`;
                        break;
                    case 'min_price':
                        labelText = `Min Price: Frw${value}`;
                        break;
                    case 'max_price':
                        labelText = `Max Price: Frw${value}`;
                        break;
                    case 'start_date':
                        labelText = `From: ${new Date(value).toLocaleDateString()}`;
                        break;
                    case 'end_date':
                        labelText = `To: ${new Date(value).toLocaleDateString()}`;
                        break;
                }
                
                tag.innerHTML = `${labelText} <button type="button" class="btn-close btn-close-white ms-2" data-field="${key}"></button>`;
                filterTags.appendChild(tag);
            }
        }
        
        // Show filter tags if we have filters
        if (hasFilters) {
            filterTags.style.display = 'flex !important';
            filterTags.setAttribute('style', 'display: flex !important');
        } else {
            filterTags.style.display = 'none !important';
            filterTags.setAttribute('style', 'display: none !important');
        }
        
        // Show loading indicator
        salesTableBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2">Filtering sales...</div>
                </td>
            </tr>
        `;
        
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Fetch filtered results
        fetch(`/sales/filter?${params.toString()}`, {
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Update stats
            totalSalesCount.textContent = data.stats.count;
            totalRevenue.textContent = 'Frw' + data.stats.total_revenue;
            totalExpenses.textContent = 'Frw' + data.stats.total_expenses;
            netIncome.textContent = 'Frw' + data.stats.net_income;
            
            // Update results count
            resultsCount.innerHTML = `Showing <span class="fw-medium">${data.sales.length}</span> results`;
            
            // Clear table
            salesTableBody.innerHTML = '';
            
            if (data.sales.length === 0) {
                salesTableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="py-3">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h5>No matching sales found</h5>
                                <p class="text-muted">Try adjusting your search criteria</p>
                                <button id="clearAllFilters" class="btn btn-outline-primary">
                                    <i class="fas fa-undo me-1"></i> Clear All Filters
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                
                document.getElementById('clearAllFilters').addEventListener('click', function() {
                    filterForm.reset();
                    applyFilters();
                });
                
                return;
            }
            
            // Add sales to table
            data.sales.forEach(sale => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="fw-medium">${sale.client_name}</div>
                        <div class="small text-muted">${sale.client_phone}</div>
                    </td>
                    <td>${sale.product ? sale.product.name : 'N/A'}</td>
                    <td>${sale.vendor ? sale.vendor.name : 'N/A'}</td>
                    <td>Frw${parseFloat(sale.vendor_price).toFixed(2)}</td>
                    <td>Frw${parseFloat(sale.expenses).toFixed(2)}</td>
                    <td class="fw-medium">Frw${parseFloat(sale.sale_price).toFixed(2)}</td>
                    <td class="fw-bold text-success">Frw${parseFloat(sale.income).toFixed(2)}</td>
                    <td class="text-end">
                        <div class="btn-group">
                            <a href="/sales/${sale.id}/edit" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/sales/${sale.id}" class="btn btn-sm btn-outline-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                `;
                salesTableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching filtered sales:', error);
            
            // Try to get more error details if available
            error.text && error.text().then(errorText => {
                try {
                    const errorData = JSON.parse(errorText);
                    console.error('Server error details:', errorData);
                } catch (e) {
                    console.error('Raw error response:', errorText);
                }
            }).catch(() => {});
            
            salesTableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-circle fa-2x mb-3"></i>
                        <h5>Error loading data</h5>
                        <p>There was a problem applying your filters. Please try again.</p>
                        <div class="small text-muted mt-2">Error: ${error.message}</div>
                    </td>
                </tr>
            `;
        });
    }
    
    // Handle filter tag removals
    filterTags.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-close')) {
            const field = e.target.getAttribute('data-field');
            document.querySelector(`[name="${field}"]`).value = '';
            applyFilters();
        }
    });
    
    // Enable search as you type with debounce
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            applyFilters();
        }, 500);
    });
    
    // Apply filters when select filters change
    document.getElementById('productFilter').addEventListener('change', applyFilters);
    document.getElementById('vendorFilter').addEventListener('change', applyFilters);
});
</script>
@endsection