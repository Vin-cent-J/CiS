<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function salesDetails()
    {
        return $this->hasMany(SalesDetail::class, 'products_id');
    }
}