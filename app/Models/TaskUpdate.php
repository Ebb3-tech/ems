<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskUpdate extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'user_id', 'status', 'update_notes', 'time_spent', 'attachment'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
