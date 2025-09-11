<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'client_name',
        'client_phone',
        'product_id',
        'vendor_id',
        'vendor_price',
        'expenses',
        'sale_price',
        'quantity',
        'comment'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getIncomeAttribute()
    {
        return $this->sale_price - ($this->vendor_price + $this->expenses);
    }
}
