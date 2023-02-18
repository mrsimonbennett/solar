<?php

namespace App\Events;

use App\Models\Estimate;
use Illuminate\Foundation\Events\Dispatchable;

class LocationSolarImportedEvent
{
    use Dispatchable;

    public function __construct(public Estimate $estimate)
    {
    }
}
