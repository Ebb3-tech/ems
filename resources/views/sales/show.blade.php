@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Sale Details</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>Date:</strong> {{ $sale->created_at->format('Y-m-d') }}</p>
            <p><strong>Client:</strong> {{ $sale->client_name }} ({{ $sale->client_phone }})</p>
            <p><strong>Product:</strong> {{ $sale->product->name }}</p>
            <p><strong>Quantity:</strong> {{ $sale->quantity }}</p>
            <p><strong>Vendor:</strong> {{ $sale->vendor->name ?? 'N/A' }}</p>
            <p><strong>Vendor Price:</strong> Frw{{ number_format($sale->vendor_price, 2) }}</p>
            <p><strong>Expenses:</strong> Frw{{ number_format($sale->expenses, 2) }}</p>
            <p><strong>Sale Price:</strong> Frw{{ number_format($sale->sale_price, 2) }}</p>
            <p><strong>Income:</strong> Frw{{ number_format($sale->income, 2) }}</p>
            <p><strong>Comment:</strong> {{ $sale->comment }}</p>
        </div>
    </div>

    <a href="{{ route('sales.index') }}" class="btn btn-secondary mt-3">
        Back to Sales List
    </a>
</div>
@endsection
