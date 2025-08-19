@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Customer Information: {{ $customer->name }} ({{ $customer->phone }})</h2>
    <a href="{{ route('callcenter.index') }}" class="btn btn-secondary mb-3">Back to Home</a>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Email:</strong> {{ $customer->email ?? 'N/A' }}</p>
            <p><strong>Location:</strong> {{ $customer->location }}</p>
            <p><strong>Occupation:</strong> {{ $customer->occupation ?? 'N/A' }}</p>
        </div>
    </div>

    <h4 class="mt-4">Requests Histories (Phone: {{ $customer->phone }})</h4>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Need</th>
                <th>Source</th>
                <th>Status</th>
                <th>Comment</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $req)
            <tr>
                <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $req->need }}</td>
                <td>
                    @if($req->source == 'walk_in_customer')
                        Walk-in Customer
                    @else
                        {{ ucfirst($req->source) }}
                    @endif
                </td>
                <td>
                    <span class="badge 
                        {{ $req->status=='pending'?'bg-warning':
                           ($req->status=='processing'?'bg-secondary':
                           ($req->status=='completed'?'bg-info':'bg-danger')) }}">
                        {{ ucfirst($req->status) }}
                    </span>
                </td>
                <td>
    <input type="text" 
           class="form-control form-control-sm comment-input" 
           data-id="{{ $req->id }}" 
           value="{{ $req->comment }}">
</td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No requests found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<script>
document.querySelectorAll('.comment-input').forEach(input => {
    input.addEventListener('change', function() {
        let requestId = this.getAttribute('data-id');
        let newComment = this.value;
        let el = this;

        fetch(`/callcenter/requests/${requestId}/update-comment`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ comment: newComment })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Green border for success
                el.style.border = "2px solid green";
                setTimeout(() => { el.style.border = ""; }, 1000);
            } else {
                // Red border if update failed
                el.style.border = "2px solid red";
            }
        })
        .catch(() => {
            el.style.border = "2px solid red";
        });
    });
});
</script>


@endsection
