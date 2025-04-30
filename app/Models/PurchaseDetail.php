<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
    public function purchases()
    {
        return $this->belongsTo(Purchase::class, 'purchases_id');
    }
}
