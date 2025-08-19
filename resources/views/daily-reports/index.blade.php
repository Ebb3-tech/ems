@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Daily Reports</h1>
   @if(auth()->user()->role != 5)
    <a href="{{ route('callcenter.index') }}" class="btn btn-secondary mb-3">Back to Home</a>
@else
    <a href="{{ route('ceo.dashboard') }}" class="btn btn-secondary mb-3">Back to Dashboard</a>
@endif



    {{-- Only non-role 5 users can add report --}}
    @if(auth()->user()->role != 5)
        <a href="{{ route('daily-reports.create') }}" class="btn btn-primary mb-3">Add New Report</a>
    @endif

    @if(auth()->user()->role == 5)
        <p>Showing all employee reports</p>
    @else
        <p>Your reports only</p>
    @endif

    @if($reports->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Employee</th>
                    <th>Content</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $report->user->name }}</td>
                        <td>{{ Str::limit($report->content, 100) }}</td>
                        <td>
                            <a href="{{ route('daily-reports.show', $report) }}" class="btn btn-sm btn-info">View</a>

                            {{-- Edit: Only owner or CEO --}}
                            @if(auth()->user()->id == $report->user_id || auth()->user()->role == 5)
                                <a href="{{ route('daily-reports.edit', $report) }}" class="btn btn-sm btn-warning">Edit</a>
                            @endif

                            {{-- Delete: Only CEO (role 5) --}}
                            @if(auth()->user()->role == 5)
                                <form action="{{ route('daily-reports.destroy', $report) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this report?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $reports->links() }}
    @else
        <p>No daily reports found.</p>
    @endif
</div>
@endsection
