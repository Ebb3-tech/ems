<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function vendor() {
    return $this->belongsTo(Vendor::class);
}
}
