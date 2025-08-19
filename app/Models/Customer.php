<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'phone', 'location', 'email', 'occupation'
    ];

    public function requests()
    {
        return $this->hasMany(CustomerRequest::class);
    }
}

