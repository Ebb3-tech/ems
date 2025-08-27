<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
    'name',
    'location',
    'phone',
    'category',
    'email',
];


    // ✅ A vendor has many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
