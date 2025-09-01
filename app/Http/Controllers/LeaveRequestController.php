<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index()
{
    $user = auth()->user();

    if ($user->role == 5) {
        // CEO sees all requests
        $leaveRequests = LeaveRequest::with(['user', 'approver'])->get();
    } else {
        // Other users see only their own requests
        $leaveRequests = LeaveRequest::with('approver')
            ->where('user_id', $user->id)
            ->get();
    }

    return view('leave-requests.index', compact('leaveRequests'));
}

public function edit(LeaveRequest $leaveRequest)
{
    // Only allow the owner of the request to edit if it's pending
    if (auth()->id() !== $leaveRequest->user_id || $leaveRequest->status != 'pending') {
        abort(403, 'Unauthorized action.');
    }

    return view('leave-requests.edit', compact('leaveRequest'));
}

public function update(Request $request, LeaveRequest $leaveRequest)
{
    // Only allow the owner to update if pending
    if (auth()->id() !== $leaveRequest->user_id || $leaveRequest->status != 'pending') {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'type' => 'required|string|in:annual,sick,maternity,unpaid',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'nullable|string',
    ]);

    $leaveRequest->update([
        'type' => $request->type,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'reason' => $request->reason,
    ]);

    return redirect()->route('leave-requests.index')->with('success', 'Leave request updated successfully.');
}



    public function create()
    {
        return view('leave-requests.create');
    }

    public function store(Request $request)
    {
       $request->validate([
    'type' => 'required|string|in:annual,sick,maternity,unpaid',
    'start_date' => 'required|date|after_or_equal:today',
    'end_date' => 'required|date|after_or_equal:start_date',
    'reason' => 'nullable|string',
]);


        LeaveRequest::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('leave-requests.index')->with('success', 'Leave request submitted successfully.');
    }




    public function destroy(LeaveRequest $leaveRequest)
    {
        // Optional: allow employees to cancel pending leave requests
        if ($leaveRequest->user_id != auth()->id()) {
            abort(403);
        }

        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')->with('success', 'Leave request deleted successfully.');
    }
}
