<?php

namespace App\Models;

use Amenadiel\JpGraph\Graph\Graph;
use Amenadiel\JpGraph\Graph\PieGraph;
use Amenadiel\JpGraph\Plot\LinePlot;
use Amenadiel\JpGraph\Plot\PiePlot;
use Amenadiel\JpGraph\Themes\UniversalTheme;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    protected $guarded = [];

    protected $casts = [
        'watts' => 'array',
        'watt_hours_period' => 'array',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'uuid');
    }

    public function getPeak()
    {
        return collect($this->watts)->map(fn($watts, $key) => ['watts' => $watts, 'datetime' => $key])->filter(
            function ($power) {
                $today = Carbon::parse($power['datetime']);

                return $today->isToday();
            }
        )->sortByDesc(
            'watts'
        )->first();
    }

    public function getPeakKwH()
    {
        return $this->getPeak()['watts'] / 1000;
    }
    public function generateChartWatts()
    {

        $watts = collect($this->watts)->map(fn($watts, $key) => ['watts' => $watts, 'datetime' => $key])->filter(
            function ($power) {
                $today = Carbon::parse($power['datetime']);

                return $today->isToday();
            }
        )->map(function ($power) {
            return ['watts' => $power['watts'], 'datetime' => Carbon::parse($power['datetime'])->format('H:i')];
        });


        $datay1 = $watts->pluck('watts')->toArray();

// Setup the graph
        $graph = new Graph(600, 600);
        $graph->SetScale("textlin");

        $theme_class = new UniversalTheme;

        $graph->SetTheme($theme_class);
        $graph->img->SetAntiAliasing(false);
        $graph->title->Set('Watts');
        $graph->SetBox(false);

        $graph->SetMargin(40, 20, 36, 63);

        $graph->img->SetAntiAliasing();

        $graph->yaxis->HideZeroLabel();
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false, false);

        $graph->xgrid->Show();
        $graph->xgrid->SetLineStyle("solid");
        $graph->xaxis->SetTickLabels($watts->pluck('datetime')->toArray());
        $graph->xgrid->SetColor('#E3E3E3');
        $graph->xaxis->SetLabelAngle(80);

// Create the first line
        $p1 = new LinePlot($datay1);
        $graph->Add($p1);
        $p1->SetColor("#6495ED");
        $p1->SetLegend('Watts');

        $graph->legend->SetFrameWeight(1);
        ob_start();
        imagejpeg($graph->Stroke('__handle'));
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;


    }

    public function generateChartPower()
    {
        $total = 0;
        $power = collect($this->watt_hours_period)->map(fn($watts, $key) => ['watts' => $watts, 'datetime' => $key])
                                                  ->filter(function ($power) {
                                                      $today = Carbon::parse($power['datetime']);

                                                      return $today->isToday();
                                                  })->map(function ($power) use (&$total) {
            $total += $power['watts'];

            return ['watts' => $total, 'datetime' => Carbon::parse($power['datetime'])->format('H:i')];
        });


        $datay1 = $power->pluck('watts')->toArray();

// Setup the graph
        $graph = new Graph(600, 600);
        $graph->SetScale("textlin");

        $theme_class = new UniversalTheme;

        $graph->SetTheme($theme_class);
        $graph->img->SetAntiAliasing(false);
        $graph->title->Set('Energy');
        $graph->SetBox(false);

        $graph->SetMargin(40, 20, 36, 63);

        $graph->img->SetAntiAliasing();

        $graph->yaxis->HideZeroLabel();
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false, false);

        $graph->xgrid->Show();
        $graph->xgrid->SetLineStyle("solid");
        $graph->xaxis->SetTickLabels($power->pluck('datetime')->toArray());
        $graph->xgrid->SetColor('#E3E3E3');
        $graph->xaxis->SetLabelAngle(80);


// Create the first line
        $p1 = new LinePlot($datay1);
        $p1->SetColor("#6495ED");
        $p1->SetLegend('WH');
        $graph->Add($p1);

        $graph->legend->SetFrameWeight(1);
        ob_start();
        imagejpeg($graph->Stroke('__handle'));
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;


    }
}
