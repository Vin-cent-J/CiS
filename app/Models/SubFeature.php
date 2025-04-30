<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubFeature extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function feature()
    {
        return $this->belongsTo(Feature::class, 'features_id');
    }

    public function configurations(): HasMany
    {
        return $this->hasMany(Configuration::class, 'sub_features_id');
    }
}
