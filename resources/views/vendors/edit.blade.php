@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-4 bg-white shadow rounded">
    <h2 class="text-xl font-bold mb-4">Edit Vendor</h2>

    <form action="{{ route('shop.vendors.update', $vendor) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="block font-semibold">Name</label>
            <input type="text" name="name" value="{{ $vendor->name }}" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label class="block font-semibold">Location</label>
            <input type="text" name="location" value="{{ $vendor->location }}" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label class="block font-semibold">Phone</label>
            <input type="text" name="phone" value="{{ $vendor->phone }}" class="w-full border rounded p-2" required>
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
            Update Vendor
        </button>
    </form>
</div>
@endsection
