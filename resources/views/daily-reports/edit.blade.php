@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl mb-4">Edit Daily Report</h1>
    <a href="{{ route('daily-reports.index') }}" class="btn btn-secondary mb-3">Back to Reports</a>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('daily-reports.update', $report) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="report_date" class="block font-bold mb-1">Report Date:</label>
            <input type="date" name="report_date" id="report_date" value="{{ old('report_date', $report->report_date) }}" class="border rounded p-2 w-full" required>
        </div>

        <div class="mb-4">
            <label for="content" class="block font-bold mb-1">Content:</label>
            <textarea name="content" id="content" rows="5" class="border rounded p-2 w-full" required>{{ old('content', $report->content) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="image" class="block font-bold mb-1">Attach Image:</label>
            <input type="file" name="image" id="image" class="border rounded p-2 w-full">
            @if($report->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $report->image) }}" alt="Report Image" class="max-w-full h-auto rounded shadow">
                </div>
            @endif
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Report</button>
    </form>
</div>
@endsection
