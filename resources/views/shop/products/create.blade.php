@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Add Product</h1>

    <a href="{{ route('shop.products.index') }}" class="btn btn-secondary mb-3">Back to Products</a>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('shop.products.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="vendor_id" class="block font-medium mb-1">Vendor</label>
            <select name="vendor_id" id="vendor_id" class="border rounded w-full p-2">
                <option value="">Select Vendor</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                        {{ $vendor->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="name" class="block font-medium mb-1">Product Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="border rounded w-full p-2">
        </div>

        <div class="mb-4">
            <label for="price" class="block font-medium mb-1">Price</label>
            <input type="number" name="price" id="price" value="{{ old('price') }}" class="border rounded w-full p-2" step="0.01">
        </div>

        <div class="mb-4">
            <label for="description" class="block font-medium mb-1">Description</label>
            <textarea name="description" id="description" class="border rounded w-full p-2">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Product</button>
    </form>
</div>
@endsection
