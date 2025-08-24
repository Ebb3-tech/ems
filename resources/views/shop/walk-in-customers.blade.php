@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Register Walk-In Customer</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('walk-in-customers.store') }}" method="POST" class="bg-white p-6 rounded shadow-md max-w-lg">
        @csrf

        <div class="mb-4">
            <label for="name" class="block font-semibold mb-1">Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="phone" class="block font-semibold mb-1">Phone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                   class="w-full border px-3 py-2 rounded">
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Register Customer
        </button>
    </form>
</div>
@endsection
