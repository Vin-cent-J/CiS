<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variant extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = [
        'products_id',
        'name',
        'value',
        'price',
        'stock',
        'deleted_at'
    ];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
}
