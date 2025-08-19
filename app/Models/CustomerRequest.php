<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRequest extends Model
{
    protected $fillable = [
        'customer_id', 'need', 'status', 'comment','source'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
