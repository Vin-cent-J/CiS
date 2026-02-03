<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'categories_id',
        'photo',
        'has_variant'
    ];

    public $timestamps = false;

    public function salesDetails()
    {
        return $this->hasMany(SalesDetail::class, 'products_id');
    }

    public function productReturns()
    {
        return $this->hasMany(ProductReturn::class, 'products_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'products_id');
    }

    public function discountRule()
    {
        return $this->hasOne(DiscountRule::class, 'products_id');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'products_id');
    }

    
}