@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    <h1 class="text-2xl font-bold mb-4">{{ $vendor->name }}</h1>
    <p class="text-gray-600 mb-6">Location: {{ $vendor->location }}</p>

    <h2 class="text-xl font-semibold mb-2">Products</h2>
    <div class="bg-white shadow rounded-lg p-4">
        @if($products->count())
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Product Name</th>
                    <th class="border px-4 py-2">Price</th>
                    <th class="border px-4 py-2">Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $product->name }}</td>
                    <td class="border px-4 py-2">${{ $product->price }}</td>
                    <td class="border px-4 py-2">{{ $product->stock }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No products yet.</p>
        @endif
    </div>

</div>
@endsection
