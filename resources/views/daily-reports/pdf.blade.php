<!DOCTYPE html>
<html>
<head>
    <title>Report #{{ $report->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 15px; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Daily Report</h2>
        <p>Submitted by: {{ $report->user->name }}</p>
        <p>Date: {{ $report->report_date }}</p>
    </div>

    <div class="section">
        <p class="label">Report Content:</p>
        <p>{{ $report->content }}</p>
    </div>

    @if($report->image)
    <div class="section">
        <p class="label">Attached Image:</p>
        <img src="{{ public_path('storage/' . $report->image) }}" style="max-width: 400px;"/>
    </div>
    @endif

    @if($report->marks !== null)
    <div class="section">
        <p class="label">Marks:</p>
        <p>{{ $report->marks }}/100</p>
    </div>
    @endif
</body>
</html>
