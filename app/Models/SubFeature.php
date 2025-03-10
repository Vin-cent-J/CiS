<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubFeature extends Model
{
    use HasFactory;

    public function feature()
    {
        return $this->belongsTo(Feature::class, 'features_id');
    }
}
