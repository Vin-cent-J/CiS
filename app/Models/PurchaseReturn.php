<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'purchases_id',
        'products_id',
        'amount',
        'date',
        'type',
        'variants_id',
    ];

    public function purchase()
    {
        return $this->belongsTo(Sale::class, 'sales_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }

    public function variant(){
        return $this->belongsTo(Variant::class, 'variants_id');
    }
}
