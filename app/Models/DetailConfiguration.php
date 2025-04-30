<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class DetailConfiguration extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function configuration(): BelongsTo
    {
        return $this->belongsTo(Configuration::class, 'configurations_id');
    }
}
