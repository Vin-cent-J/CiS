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
        'photo'
    ];

    public $timestamps = false;

    public function salesDetails()
    {
        return $this->hasMany(SalesDetail::class, 'products_id');
    }
}