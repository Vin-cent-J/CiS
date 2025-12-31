<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    use HasFactory;


    public $timestamps = false;
    protected $primaryKey = 'sales_id';
    public $fillable = [
        'sales_id',
        'products_id',
        'amount',
        'price',
        'discount',
        'discount_type',
        'total_return',
        'variants_id',
    ];
    public function sales()
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
