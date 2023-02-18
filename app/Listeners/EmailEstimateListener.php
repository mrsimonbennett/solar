<?php

namespace App\Listeners;

use App\Events\LocationSolarImportedEvent;
use App\Mail\DailySolarMail;
use App\Mail\EmailDailySolarMail;
use Illuminate\Support\Facades\Mail;

class EmailEstimateListener
{
    public function __construct()
    {
    }

    public function handle(LocationSolarImportedEvent $event)
    {
        logger('Emailing estimate',$event->estimate->toArray());


        Mail::to($event->estimate->location->email)->send(new EmailDailySolarMail($event->estimate));
    }
}
