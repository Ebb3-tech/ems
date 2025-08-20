@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">My Tasks</h1>

    {{-- Tasks Card --}}
    <div class="row g-4 mb-5">
    <div class="col-md-2">
        <div class="card text-white bg-dark h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Total Tasks</h5>
                <p class="card-text fs-2">{{ $tasksCount['total'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-warning h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Pending</h5>
                <p class="card-text fs-2">{{ $tasksCount['pending'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-secondary h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">In Progress</h5>
                <p class="card-text fs-2">{{ $tasksCount['in_progress'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-info h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Completed</h5>
                <p class="card-text fs-2">{{ $tasksCount['completed'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-danger h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Canceled</h5>
                <p class="card-text fs-2">{{ $tasksCount['canceled'] }}</p>
            </div>
        </div>
    </div>
</div>


    {{-- Tasks Table --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Deadline</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tasks-table-body">
                @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>{{ ucfirst($task->priority) }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <select class="form-select status-dropdown me-2" data-id="{{ $task->id }}">
                                <option value="pending" {{ $task->status=='pending'?'selected':'' }}>Pending</option>
                                <option value="in_progress" {{ $task->status=='in_progress'?'selected':'' }}>In Progress</option>
                                <option value="completed" {{ $task->status=='completed'?'selected':'' }}>Completed</option>
                                <option value="canceled" {{ $task->status=='canceled'?'selected':'' }}>Canceled</option>
                            </select>
                            
                        </div>
                    </td>
                    <td>
                        {{ $task->deadline ? $task->deadline->format('Y-m-d H:i') : 'N/A' }}
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary update-status-btn" data-id="{{ $task->id }}">
                                Update
                            </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('tasks-table-body');

    // Update status via button
    tbody.addEventListener('click', function(e) {
        if(e.target && e.target.classList.contains('update-status-btn')) {
            const row = e.target.closest('tr');
            const dropdown = row.querySelector('.status-dropdown');
            const taskId = dropdown.getAttribute('data-id');
            const newStatus = dropdown.value;

            fetch(`/callcenter/callcenter/tasks/${taskId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({status: newStatus})
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert('Status updated successfully!');
                } else {
                    alert('Failed to update status.');
                }
            })
            .catch(err => console.error(err));
        }
    });

    // Optional: Make card clickable to scroll to tasks table
    const tasksCard = document.getElementById('tasks-card');
    tasksCard.addEventListener('click', () => {
        window.scrollTo({ top: tbody.offsetTop - 50, behavior: 'smooth' });
    });
});
</script>
@endsection
