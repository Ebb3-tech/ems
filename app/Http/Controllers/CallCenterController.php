<?php


namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerRequest;
use Illuminate\Http\Request;

class CallCenterController extends Controller
{
    public function index()
    {
        $requests = CustomerRequest::with('customer')->latest()->get();
        return view('callcenter.index', compact('requests'));
    }

    public function create()
    {
        return view('callcenter.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'location' => 'required|string',
            'email' => 'nullable|email',
            'occupation' => 'nullable|string',
            'need' => 'required|string',
            'status' => 'required|in:pending,processing,completed,canceled',
            'comment' => 'nullable|string',
            'source' => 'required|in:instagram,tiktok,facebook,linkedin,walk_in_customer,twitter,snapchat'
        ]);

        // Check if customer exists
        $customer = Customer::firstOrCreate(
            ['phone' => $validated['phone']],
            [
                'name' => $validated['name'],
                'location' => $validated['location'],
                'email' => $validated['email'] ?? null,
                'occupation' => $validated['occupation'] ?? null
            ]
        );

        // Save request
        CustomerRequest::create([
            'customer_id' => $customer->id,
            'need' => $validated['need'],
            'status' => $validated['status'],
            'comment' => $validated['comment'] ?? null,
            'source' => $validated['source'],
        ]);

        return redirect()->route('callcenter.index')->with('success', 'Customer request saved!');
    }
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,processing,completed,canceled'
    ]);

    $customerRequest = CustomerRequest::findOrFail($id);
    $customerRequest->status = $request->status;
    $customerRequest->save();

    return response()->json(['success' => true]);
}
public function history($id)
{
    // Get the customer
    $customer = Customer::findOrFail($id);

    // Fetch all requests where customer phone matches
    $requests = CustomerRequest::whereHas('customer', function($q) use ($customer) {
        $q->where('phone', $customer->phone);
    })->with('customer')->latest()->get();

    return view('callcenter.history', compact('customer', 'requests'));
}



     public function search(Request $request)
{
    $term = $request->get('term', '');

    $customers = Customer::where('name', 'LIKE', "%$term%")
        ->orWhere('phone', 'LIKE', "%$term%")
        ->limit(10)
        ->get(['id', 'name', 'phone', 'email', 'location', 'occupation']);

    // Attach latest source from requests
    $customers->each(function($customer) {
        $customer->latest_source = $customer->requests()->latest()->value('source');
    });

    return response()->json($customers);
}


    public function filterRequests(Request $request)
{
    $term = $request->get('term', '');
    $status = $request->get('status', '');

    $requests = CustomerRequest::with('customer')
        ->when($term, function($q) use ($term) {
            $q->whereHas('customer', function($q2) use ($term) {
                $q2->where('name', 'like', "%{$term}%")
                   ->orWhere('phone', 'like', "%{$term}%")
                   ->orWhere('source', 'like', "%{$term}%");
            });
        })
        ->when($status, function($q) use ($status) {
            $q->where('status', $status);
        })
        ->latest()
        ->take(50)
        ->get();

    return response()->json($requests);
}
public function updateComment(Request $request, $id)
{
    $request->validate([
        'comment' => 'nullable|string'
    ]);

    $customerRequest = CustomerRequest::findOrFail($id);
    $customerRequest->comment = $request->comment;
    $customerRequest->save();

    return response()->json(['success' => true, 'comment' => $customerRequest->comment]);
}



}

