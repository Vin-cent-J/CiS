<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id');
    }

    public function salesDetails()
    {
        return $this->hasMany(SalesDetail::class, 'sales_id');
    }
}
