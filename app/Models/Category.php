<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'name',
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function discountRules()
    {
        return $this->belongsToMany(DiscountRule::class, 'discounted_categories', 'categories_id', 'discount_rules_id');
    }

}
