<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountRule extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'minimum',
        'line_discount',
        'sales_type',
        'products_id',
        'categories_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
}
