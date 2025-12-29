<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    public $fillable = [
        'name',
        'phone',
        'address',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'suppliers_id');
    }
}
