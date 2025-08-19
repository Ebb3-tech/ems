@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Register Customer Request</h2>
    <a href="{{ route('callcenter.index') }}" class="btn btn-secondary mb-3">Back to Home</a>

    <form action="{{ route('callcenter.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Search Customer</label>
            <input type="text" id="customerSearch" class="form-control" placeholder="Type name or phone">
            <ul id="customerResults" class="list-group position-absolute w-50" style="z-index:1000;"></ul>
        </div>

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Location</label>
            <input type="text" name="location" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email (optional)</label>
            <input type="email" name="email" id="email" class="form-control">
        </div>

        <div class="mb-3">
            <label>Occupation</label>
            <input type="text" name="occupation" class="form-control">
        </div>
                <div class="mb-3">
    <label for="source" class="form-label">Source</label>
    <select name="source" id="source" class="form-select" required>
        <option value="">-- Select Source --</option>
        <option value="instagram">Instagram</option>
        <option value="tiktok">TikTok</option>
        <option value="facebook">Facebook</option>
        <option value="linkedin">LinkedIn</option>
        <option value="twitter">Twitter(X)</option>
        <option value="snapchat">Snapchat</option>
        <option value="walk_in_customer">Walk-in Customer</option>
    </select>
</div>

        <div class="mb-3">
            <label>What does the customer need?</label>
            <input type="text" name="need" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Comment</label>
            <textarea name="comment" class="form-control"></textarea>
        </div>



        <button type="submit" class="btn btn-primary">Save Request</button>
    </form>
</div>

<script>
let searchInput = document.getElementById('customerSearch');
let resultsBox = document.getElementById('customerResults');

searchInput.addEventListener('keyup', function() {
    let query = this.value;
    resultsBox.innerHTML = '';

    if (query.length < 2) return;

    fetch(`/callcenter/search?term=${encodeURIComponent(query)}`)
    .then(res => res.json())
    .then(data => {
        resultsBox.innerHTML = '';
        data.forEach(customer => {
            let li = document.createElement('li');
            li.classList.add('list-group-item');
            li.textContent = `${customer.name} - ${customer.phone}`;
            li.style.cursor = 'pointer';

            li.addEventListener('click', function() {
                document.getElementById('name').value = customer.name;
                document.getElementById('phone').value = customer.phone;
                document.getElementById('email').value = customer.email ?? '';
                document.querySelector('input[name="location"]').value = customer.location ?? '';
                document.querySelector('input[name="occupation"]').value = customer.occupation ?? '';
                if (customer.latest_source) {
                    document.getElementById('source').value = customer.latest_source;
                }

    resultsBox.innerHTML = '';
    searchInput.value = '';
});


            resultsBox.appendChild(li);
        });
    });

});

</script>
@endsection
