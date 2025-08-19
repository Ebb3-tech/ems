@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Attendance Records</h1>

@if ($attendances->count())
    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">Date</th>
                <th class="border border-gray-300 px-4 py-2">Clock In</th>
                <th class="border border-gray-300 px-4 py-2">Clock Out</th>
                <th class="border border-gray-300 px-4 py-2">Status</th>
                <th class="border border-gray-300 px-4 py-2">Notes</th>
                <th class="border border-gray-300 px-4 py-2">Made By</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance->date->format('Y-m-d') }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance->clock_in ?? '-' }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance->clock_out ?? '-' }}</td>
                <td class="border border-gray-300 px-4 py-2 capitalize">{{ $attendance->status }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance->notes ?? '-' }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $attendance->user->name ?? 'N/A' }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <a href="{{ route('attendance.edit', $attendance) }}" class="btn btn-primary btn-sm">Edit</a>

                    @if(auth()->user()->role == 5)
                        <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete attendance?')" class="text-red-600 hover:underline ml-2">Delete</button>
                        </form>
                    @endif
                </td>
                
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $attendances->links() }}
@else
    <p>No attendance records found.</p>
@endif

<a href="{{ route('attendance.create') }}" class="inline-block mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add Attendance</a>
@endsection
