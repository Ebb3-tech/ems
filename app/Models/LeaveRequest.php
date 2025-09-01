<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'start_date', 'end_date', 'reason', 'status', 'approved_by'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
];

}
