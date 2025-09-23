<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'sales_id',
        'products_id',
        'amount',
        'date',
        'type',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sales_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
}
