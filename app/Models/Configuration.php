<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function detailConfigurations(): HasMany{
        return $this->hasMany(DetailConfiguration::class, 'configurations_id');
    }

    public function subFeature(): BelongsTo
    {
        return $this->belongsTo(SubFeature::class, 'sub_features_id');
    }
}
