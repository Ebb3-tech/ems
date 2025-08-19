<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index()
    {
        // CEO, HR or Manager can see all; employees see their own requests
        $user = auth()->user();

        if ($user->hasAnyRole(['admin', 'ceo', 'hr', 'manager'])) {
            $leaveRequests = LeaveRequest::with(['user', 'approver'])->get();
        } else {
            $leaveRequests = LeaveRequest::with('approver')->where('user_id', $user->id)->get();
        }

        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        return view('leave_requests.create');
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

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $user = auth()->user();

        // Only allow approval/rejection by certain roles
        if (!$user->can('approve-leave')) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $leaveRequest->status = $request->status;
        $leaveRequest->approver_id = $user->id;
        $leaveRequest->save();

        return redirect()->route('leave-requests.index')->with('success', 'Leave request updated successfully.');
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
