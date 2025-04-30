<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    public function salesDetails()
    {
        return $this->hasMany(SalesDetail::class, 'discounts_id');
    }
}
