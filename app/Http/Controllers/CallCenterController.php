<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Customer;
use App\Models\CustomerRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CallCenterController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // Get tasks assigned to the logged-in user
    $tasks = Task::where('assigned_to', $user->id)
                 ->latest()
                 ->take(5)
                 ->get();

    // Only users with role 5 or 2 can see all requests
    if (in_array($user->role, [2, 5])) {
        $requests = CustomerRequest::with('customer')->get();
    } else {
        // Other users: empty collection initially
        $requests = collect();
    }

    return view('callcenter.index', compact('requests', 'tasks'));
}


    public function myTasks()
    {
        $userId = Auth::id();
        $tasks = Task::where('assigned_to', $userId)->latest()->get();

        $tasksCount = [
            'total' => $tasks->count(),
            'pending' => $tasks->where('status', 'pending')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'completed' => $tasks->where('status', 'completed')->count(),
            'canceled' => $tasks->where('status', 'canceled')->count(),
        ];

        return view('callcenter.my-tasks', compact('tasks', 'tasksCount'));
    }

    public function updateTaskStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,canceled',
        ]);

        $task->update(['status' => $request->status]);

        return response()->json(['success' => true]);
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

        $customer = Customer::firstOrCreate(
            ['phone' => $validated['phone']],
            [
                'name' => $validated['name'],
                'location' => $validated['location'],
                'email' => $validated['email'] ?? null,
                'occupation' => $validated['occupation'] ?? null
            ]
        );

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
        $customer = Customer::findOrFail($id);

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

        $customers->each(function($customer) {
            $customer->latest_source = $customer->requests()->latest()->value('source');
        });

        return response()->json($customers);
    }

    public function filterRequests(Request $request)
{
    $name = $request->get('name', '');
    $phone = $request->get('phone', '');
    $source = $request->get('source', '');
    $status = $request->get('status', '');

    $query = CustomerRequest::with('customer');
    
    // Filter by customer name
    if (!empty($name)) {
        $query->whereHas('customer', function($q) use ($name) {
            $q->where('name', 'like', "%{$name}%");
        });
    }
    
    // Filter by customer phone
    if (!empty($phone)) {
        $query->whereHas('customer', function($q) use ($phone) {
            $q->where('phone', 'like', "%{$phone}%");
        });
    }
    
    // Filter by source
    if (!empty($source)) {
        $query->where('source', $source);
    }
    
    // Filter by status
    if (!empty($status)) {
        $query->where('status', $status);
    }
    
    $requests = $query->latest()->take(50)->get();
    
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
