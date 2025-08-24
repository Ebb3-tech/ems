<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyReportController extends Controller
{
    // List all daily reports
    public function index()
{
    $user = auth()->user();

    if ($user->role == 5) {
        // CEO/Admin sees all reports
        $reports = DailyReport::with('user')->latest()->paginate(20);
    } else {
        // Other users see only their reports
        $reports = DailyReport::with('user')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(20);
    }

    return view('daily-reports.index', compact('reports'));
}

    // Show form to create a new daily report
    public function create()
    {
        return view('daily-reports.create');
    }

    // Store a new daily report in database
    public function store(Request $request)
{
    $request->validate([
        'report_date' => 'required|date',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    $data = $request->only('report_date', 'content');
    $data['user_id'] = auth()->id();

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('daily_reports', 'public');
        $data['image'] = $path;
    }

    DailyReport::create($data);

    return redirect()->route('daily-reports.index')->with('success', 'Report created successfully.');
}


    // Show a single daily report
    public function show(DailyReport $dailyReport)
{
    // Pass the report to the view
    return view('daily-reports.show', ['report' => $dailyReport]);
}

   public function edit(DailyReport $dailyReport)
{
    // ✅ Anyone can edit
    return view('daily-reports.edit', ['report' => $dailyReport]);
}

public function update(Request $request, DailyReport $dailyReport)
{
    // ✅ Anyone can update
    $request->validate([
        'report_date' => 'required|date',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    $dailyReport->update([
        'report_date' => $request->report_date,
        'content' => $request->content,
        'image' => $request->hasFile('image')
            ? $request->file('image')->store('daily_reports', 'public')
            : $dailyReport->image,
    ]);

    return redirect()->route('daily-reports.index')
        ->with('success', 'Daily report updated successfully.');
}

public function destroy(DailyReport $dailyReport)
{
    $user = Auth::user();

    // ✅ Only CEO (role=5) can delete
    if ($user->role != 5) {
        abort(403, 'Only the CEO can delete reports.');
    }

    $dailyReport->delete();

    return redirect()->route('daily-reports.index')
        ->with('success', 'Daily report deleted successfully.');
}

}
