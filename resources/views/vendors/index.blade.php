@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4">

    <h1 class="text-2xl font-bold mb-6">Vendors</h1>

    <!-- Add Vendor Form -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <form action="{{ route('vendors.store') }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="Vendor Name" class="border rounded px-2 py-1 flex-1" required>
            <input type="text" name="location" placeholder="Location" class="border rounded px-2 py-1 flex-1">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Vendor</button>
        </form>
    </div>

    <!-- Vendors Table -->
    <div class="bg-gray-50 shadow rounded-lg p-4">

        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2 text-left">#</th>
                    <th class="border px-4 py-2 text-left">Name</th>
                    <th class="border px-4 py-2 text-left">Location</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendors as $vendor)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $vendor->name }}</td>
                    <td class="border px-4 py-2">{{ $vendor->location }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('vendors.show', $vendor) }}" class="text-blue-500 hover:underline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
