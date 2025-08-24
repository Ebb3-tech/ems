<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkInCustomer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'added_by',
        'need',
        'status',
        'comment',
    ];

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}


