<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    use HasFactory;


    public $timestamps = false;
    public $fillable = [
        'sales_id',
        'products_id',
        'amount',
        'price',
        'discount',
        'discounts_id',
    ];
    public function sales()
    {
        return $this->belongsTo(Sale::class, 'sales_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discounts_id');
    }
}
