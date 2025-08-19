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
    if (auth()->user()->role === 'CEO') {
        // CEO sees all reports
        $reports = DailyReport::with('user')->orderBy('report_date', 'desc')->paginate(10);
    } else {
        // Employees see only their own reports
        $reports = DailyReport::with('user')
            ->where('user_id', auth()->id())
            ->orderBy('report_date', 'desc')
            ->paginate(10);
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
        ]);

        DailyReport::create([
            'user_id' => Auth::id(),
            'report_date' => $request->report_date,
            'content' => $request->content,
        ]);

        return redirect()->route('daily-reports.index')->with('success', 'Daily report created successfully.');
    }

    // Show a single daily report
    public function show(DailyReport $dailyReport)
    {
        return view('daily-reports.show', compact('dailyReport'));
    }

    // Show form to edit an existing daily report
    public function edit(DailyReport $dailyReport)
{
    if (auth()->user()->role !== 'CEO' && $dailyReport->user_id !== auth()->id()) {
        abort(403);
    }
    return view('daily-reports.edit', compact('dailyReport'));
}

// Similarly for update and destroy:
public function update(Request $request, DailyReport $dailyReport)
{
    if (auth()->user()->role !== 'CEO' && $dailyReport->user_id !== auth()->id()) {
        abort(403);
    }
    // validate and update...
}

public function destroy(DailyReport $dailyReport)
{
    if (auth()->user()->role !== 'CEO' && $dailyReport->user_id !== auth()->id()) {
        abort(403);
    }
    $dailyReport->delete();
    // redirect back...
}

}
