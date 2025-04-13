<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feature extends Model
{
    use HasFactory;

    protected $id = "features_id";
    public function subFeatures()
    {
        return $this->hasMany(SubFeature::class, 'features_id');
    }

    public function detailConfigurations()
    {
        return $this->hasManyThrough(DetailConfiguration::class, Configuration::class, 'features_id', 'configurations_id');
    }
}
