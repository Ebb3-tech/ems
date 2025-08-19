@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Departments</h1>
    <a href="{{ route('departments.create') }}" class="btn btn-primary mb-3">Add Department</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr><th>Name</th><th>Description</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($departments as $department)
            <tr>
                <td>{{ $department->name }}</td>
                <td>{{ $department->description }}</td>
                <td>
                    <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('departments.destroy', $department) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this department?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
