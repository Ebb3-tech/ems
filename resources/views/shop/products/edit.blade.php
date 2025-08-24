@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Product</h1>

    <form action="{{ route('shop.products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" name="name" value="{{ $product->name }}" class="form-control" id="name">
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" value="{{ $product->price }}" class="form-control" id="price">
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>
@endsection
