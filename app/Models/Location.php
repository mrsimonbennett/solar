<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function estimates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Estimate::class, 'location_id', 'uuid');
    }
}
