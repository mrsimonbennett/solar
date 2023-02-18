<?php

namespace App\Console\Commands\Solar;

use App\Events\LocationSolarImportedEvent;
use App\Models\Location;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class ImportTodaysEstimatesCommand extends Command
{
    protected $signature = 'solar:import-todays-estimates';

    protected $description = 'Command description';


    public function handle()
    {
        $client = new Client([
                                 'base_uri' => 'https://api.forecast.solar',
                                 'timeout'  => 2.0,
                             ]);

        foreach (Location::all() as $location) {
            try {
                $reply = $client->get(
                    vsprintf('/7guAuTH3bdR4BuX3/estimate/%s/%s/%s/%s/%s', [
                                                          $location->latitude,
                                                          $location->longitude,
                                                          $location->declination,
                                                          $location->azimuth,
                                                          $location->kwh,
                                                      ]
                    )
                );
                $data = json_decode($reply->getBody()->getContents(), true);

                dump($data);
                $data = $data['result'];
                $insert = [
                    'watts'                   => $data['watts'],
                    'watt_hours_period'       => $data['watt_hours_period'],
                    'today_watt_hours_day'    => $data['watt_hours_day'][Carbon::now()->format('Y-m-d')],
                    'tomorrow_watt_hours_day' => $data['watt_hours_day'][Carbon::now()->addDay()->format('Y-m-d')],
                ];
                $estimate = $location->estimates()->create($insert);
                event(new  LocationSolarImportedEvent($estimate));


            } catch (GuzzleException $e) {
                $this->error($e->getResponse()->getBody());
                continue;
            }
        }
    }
}
