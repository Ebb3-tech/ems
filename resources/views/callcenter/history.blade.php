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

    <h4 class="mt-4">Customer History (Phone: {{ $customer->phone }})</h4>
    
    <div class="card">
        <div class="card-body chat-history">
            @if($requests->isEmpty())
                <p class="text-center">No requests found</p>
            @else
                @foreach($requests as $req)
                    <div class="history-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">{{ $req->created_at->format('Y-m-d H:i') }}</span>
                            <span class="badge 
                                {{ $req->status=='pending'?'bg-warning':
                                   ($req->status=='processing'?'bg-secondary':
                                   ($req->status=='completed'?'bg-info':'bg-danger')) }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </div>
                        <div class="history-content mt-1 p-3 border rounded">
                            <p class="mb-1"><strong>Need:</strong> {{ $req->need }}</p>
                            <p class="mb-1"><strong>Source:</strong> 
                                @if($req->source == 'walk_in_customer')
                                    Walk-in Customer
                                @else
                                    {{ ucfirst($req->source) }}
                                @endif
                            </p>
                            <div class="mt-2">
                                <label class="form-label small">Comment:</label>
                                <input type="text" 
                                       class="form-control form-control-sm comment-input" 
                                       data-id="{{ $req->id }}" 
                                       value="{{ $req->comment }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<style>
.chat-history {
    max-height: 600px;
    overflow-y: auto;
}
.history-content {
    background-color: #f8f9fa;
}
</style>

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