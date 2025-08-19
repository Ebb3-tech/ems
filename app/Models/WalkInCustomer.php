<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalkInCustomer extends Model
{
    public function addedBy() {
    return $this->belongsTo(User::class, 'added_by');
}
}
