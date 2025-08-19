<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'month', 'basic_salary', 'allowances', 'deductions', 'net_salary', 'status', 'paid_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
