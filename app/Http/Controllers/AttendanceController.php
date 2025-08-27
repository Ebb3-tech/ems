<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        // Check if user already has an incomplete attendance record for today
        $today = Carbon::today()->format('Y-m-d');
        $existingRecord = Attendance::where('user_id', auth()->id())
            ->whereDate('date', $today)
            ->whereNull('clock_out')
            ->first();
            
        if ($existingRecord) {
            // Redirect to edit page to complete the record
            return redirect()->route('attendance.edit', $existingRecord)
                ->with('info', 'You already clocked in today. Please clock out instead.');
        }
        
        return view('attendance.create', [
            'currentTime' => Carbon::now()->format('H:i')
        ]);
    }

    // Store a newly created attendance record in storage
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,leave',
            'clock_in' => 'required_if:status,present,late|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        Attendance::create([
            'user_id' => auth()->id(),
            'date' => $request->date,
            'status' => $request->status,
            'clock_in' => $request->clock_in,
            'clock_out' => null, // Always null on creation (clock-in)
            'notes' => $request->notes,
        ]);

        return redirect()->route('attendance.index')
            ->with('success', 'Clock-in recorded successfully! Remember to clock out when you finish work.');
    }

   public function edit(Attendance $attendance)
{
    if (auth()->id() != $attendance->user_id && auth()->user()->role != 5) {
        return redirect()->route('attendance.index')
            ->with('error', 'You are not authorized to edit this attendance record.');
    }
    
    return view('attendance.edit', [
        'attendance' => $attendance,
        'currentTime' => Carbon::now()->format('H:i')
    ]);
}

public function update(Request $request, Attendance $attendance)
{
    // Authorization check
    if (auth()->id() != $attendance->user_id && auth()->user()->role != 5) {
        return redirect()->route('attendance.index')
            ->with('error', 'You are not authorized to update this attendance record.');
    }

    $request->validate([
        'clock_out' => 'required|date_format:H:i',
        'notes' => 'nullable|string',
    ]);

    // Get the original clock_in time from database
    $clockIn = Carbon::parse($attendance->clock_in);
    $date = Carbon::parse($attendance->date)->format('Y-m-d');
    
    // Parse the clock_out time
    $clockOut = Carbon::createFromFormat('H:i', $request->clock_out);
    $clockOut->setDateFrom(Carbon::parse($date));
    
    // If clock-out is earlier than clock-in, assume it's the next day
    if ($clockOut->lt($clockIn)) {
        $clockOut->addDay();
    }
    
    // Calculate hours worked
    $hoursWorked = $clockOut->floatDiffInHours($clockIn);
    
    // Remove the minimum time validation - this line is removed:
    // if ($hoursWorked < 0.016) { ... }

    $attendance->update([
        'clock_out' => $request->clock_out,
        'notes' => $request->notes,
        'hours_worked' => $hoursWorked
    ]);

    return redirect()->route('attendance.index')
        ->with('success', 'You have successfully clocked out! Your working hours: ' . 
               number_format($hoursWorked, 2) . ' hours.');
}

    public function destroy(Attendance $attendance)
    {
        if (auth()->id() != $attendance->user_id && auth()->user()->role != 5) {
            return redirect()->route('attendance.index')
                ->with('error', 'You are not authorized to delete this attendance record.');
        }
        
        $attendance->delete();
        return redirect()->route('attendance.index')
            ->with('success', 'Attendance deleted successfully.');
    }
}