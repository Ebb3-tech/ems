@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    <h1 class="text-2xl font-bold mb-6">Register Walk-in Customer</h1>

    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <form action="{{ route('walk-in-customers.store') }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="Customer Name" class="border rounded px-2 py-1 flex-1" required>
            <input type="text" name="phone" placeholder="Phone (optional)" class="border rounded px-2 py-1 flex-1">
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Register</button>
        </form>
    </div>

    <!-- Optional: show recent walk-in customers -->
    <div class="bg-white shadow rounded-lg p-4">
        <h2 class="text-xl font-semibold mb-2">Recent Walk-in Customers</h2>
        <ul>
            @foreach($recentCustomers as $customer)
                <li>{{ $customer->name }} - {{ $customer->phone ?? 'No phone' }}</li>
            @endforeach
        </ul>
    </div>

</div>
@endsection
