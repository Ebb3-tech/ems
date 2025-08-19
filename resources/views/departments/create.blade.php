@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($department) ? 'Edit Department' : 'Add Department' }}</h1>

    <form action="{{ isset($department) ? route('departments.update', $department) : route('departments.store') }}" method="POST">
        @csrf
        @if(isset($department)) @method('PUT') @endif

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $department->name ?? '') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $department->description ?? '') }}</textarea>
        </div>

        <button class="btn btn-primary" type="submit">{{ isset($department) ? 'Update' : 'Save' }}</button>
    </form>
</div>
@endsection
