<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'department_id', 'position',
        'phone', 'address', 'date_of_birth', 'hire_date', 'salary', 'profile_photo',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function assignedTasks()  // Tasks assigned **to** this user
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks()  // Tasks assigned **by** this user
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    public function taskUpdates()
    {
        return $this->hasMany(TaskUpdate::class);
    }
    public function isCEO()
    {
        return (string) $this->role === '5';
    }
    public function notifications()
{
    return $this->hasMany(Notification::class, 'user_id');
}
    public function sentNotifications()
    {
        return $this->hasMany(Notification::class, 'created_by');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isEmployee()
    {
        return $this->hasRole('employee');
    }

    public function isManager()
    {
        return $this->hasRole('manager');
    }

}
