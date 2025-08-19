@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl md:text-3xl font-bold mb-4 text-center md:text-left">Daily Report Details</h1>

    <div class="flex justify-center md:justify-start mb-3">
        <a href="{{ route('daily-reports.index') }}" class="btn btn-secondary px-4 py-2 text-sm md:text-base">Back to Reports</a>
    </div>

    <div class="bg-white border rounded shadow p-4 space-y-4">
        <p><strong>Date:</strong> <span class="text-gray-700">{{ $report->report_date }}</span></p>
        <p><strong>Employee:</strong> <span class="text-gray-700">{{ $report->user->name }}</span></p>

        <div>
            <p class="mt-2 font-semibold">Content:</p>
            <p class="border p-2 rounded bg-gray-50 break-words">{{ $report->content }}</p>
        </div>


    <p class="font-semibold">Attached Image:</p>
    <div class="mt-2 flex justify-center md:justify-start">
        <img src="{{ asset('storage/' . $report->image) }}" alt="Report Image" style="max-width: 25%; height: auto; border-radius: 8px;" class="shadow">
    </div>


    </div>
</div>
@endsection
