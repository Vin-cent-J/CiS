<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'phone_number',
        'address',
    ];

    public $timestamps = false;

    public function sales()
    {
        return $this->hasMany(Sale::class, 'customers_id');
    }
}
