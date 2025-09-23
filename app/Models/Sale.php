<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $fillable = [
        'customers_id',
        'date',
        'total',
        'shipping_date',
        'return_date',
        'return_type',
        'total_debt',
        'payment_methods',
        'sales_type',
        'discount',
        'discount_type',
        'shipping_method'
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id');
    }

    public function salesDetails()
    {
        return $this->hasMany(SalesDetail::class, 'sales_id');
    }

    public function productReturns()
    {
        return $this->hasMany(ProductReturn::class, 'sales_id');
    }
}
