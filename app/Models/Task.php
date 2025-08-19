<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'assigned_by', 'assigned_to', 
        'department_id', 'priority', 'status', 'deadline', 'attachment'
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function updates()
    {
        return $this->hasMany(TaskUpdate::class);
    }
}
