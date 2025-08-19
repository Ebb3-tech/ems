@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Shop Dashboard</h1>

    {{-- Dashboard Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card text-white bg-primary h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Vendors</h5>
                    <p class="card-text fs-2">{{ $vendorsCount }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('vendors.index') }}" class="text-white">View Vendors</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text fs-2">{{ $productsCount }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('products.index') }}" class="text-white">View Products</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Walk-in Customers</h5>
                    <p class="card-text fs-2">{{ $walkInCustomersCount }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('walk-in-customers.index') }}" class="text-white">View Customers</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="mt-4 mb-5">
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('vendors.create') }}" class="btn btn-primary w-100 py-3 shadow-sm">
                    Add Vendor
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('products.create') }}" class="btn btn-success w-100 py-3 shadow-sm">
                    Add Product
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('walk-in-customers.create') }}" class="btn btn-warning w-100 py-3 shadow-sm">
                    Register Customer
                </a>
            </div>
        </div>
    </div>

    {{-- Latest Vendors Table --}}
    <div class="table-responsive">
        <h4>Recent Vendors</h4>
        <table class="table table-hover align-middle shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Vendor::latest()->take(5)->get() as $vendor)
                <tr>
                    <td>{{ $vendor->name }}</td>
                    <td>{{ $vendor->email }}</td>
                    <td>{{ $vendor->phone }}</td>
                    <td>{{ $vendor->location }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Latest Products Table --}}
    <div class="table-responsive mt-4">
        <h4>Recent Products</h4>
        <table class="table table-hover align-middle shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Vendor</th>
                    <th>Price</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Product::with('vendor')->latest()->take(5)->get() as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->vendor->name ?? '-' }}</td>
                    <td>${{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection