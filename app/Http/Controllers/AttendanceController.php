<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // Display a listing of attendance records
public function index()
{
    $user = auth()->user();

    if ($user->role == 5) { // CEO or admin sees all
        $attendances = Attendance::with('user')->latest()->paginate(20);
    } else {
        $attendances = Attendance::with('user')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(20);
    }

    return view('attendance.index', compact('attendances'));
}



    // Show the form for creating a new attendance record
    public function create()
    {
        return view('attendance.create');
    }

    // Store a newly created attendance record in storage
    public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'status' => 'required|in:present,absent,late,leave',
        'clock_in' => 'nullable|date_format:H:i',
        'clock_out' => 'nullable|date_format:H:i',
        'notes' => 'nullable|string',
    ]);

    Attendance::create([
        'user_id' => auth()->id(),
        'date' => $request->date,
        'status' => $request->status,
        'clock_in' => $request->clock_in,
        'clock_out' => $request->clock_out,
        'notes' => $request->notes,
    ]);

    return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully.');
}

    // Display the specified attendance record
    public function show(Attendance $attendance)
    {
        return view('attendance.show', compact('attendance'));
    }

    // Show the form for editing the specified attendance record
    public function edit(Attendance $attendance)
    {
        return view('attendance.edit', compact('attendance'));
    }

    // Update the specified attendance record in storage
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late',
        ]);

        $attendance->update([
            'date' => $request->date,
            'status' => $request->status,
        ]);

        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully.');
    }

    // Remove the specified attendance record from storage
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendance.index')->with('success', 'Attendance deleted successfully.');
    }
}
