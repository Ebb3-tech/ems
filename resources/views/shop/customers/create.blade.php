@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-4 bg-white shadow rounded">
    <h2 class="text-xl font-bold mb-4">Register Customer</h2>

    <form action="{{ route('shop.customers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="block font-semibold">Name</label>
            <input type="text" name="name" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label class="block font-semibold">Phone</label>
            <input type="text" name="phone" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label class="block font-semibold">Location</label>
            <input type="text" name="location" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label class="block font-semibold">Email</label>
            <input type="email" name="email" class="w-full border rounded p-2">
        </div>
        <div class="mb-3">
            <label class="block font-semibold">Occupation</label>
            <input type="text" name="occupation" class="w-full border rounded p-2">
        </div>
        <div class="mb-3">
            <label class="block font-semibold">Need / Request</label>
            <input type="text" name="need" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
            <label class="block font-semibold">Source</label>
            <select name="source" class="w-full border rounded p-2" required>
                <option value="walk_in_customer">Walk-in Customer</option>
                <option value="instagram">Instagram</option>
                <option value="tiktok">TikTok</option>
                <option value="facebook">Facebook</option>
                <option value="linkedin">LinkedIn</option>
                <option value="twitter">Twitter</option>
                <option value="snapchat">Snapchat</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            Save Customer
        </button>
    </form>
</div>
@endsection
