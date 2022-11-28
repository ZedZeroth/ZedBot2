<?php

declare(strict_types=1);

namespace App\Http\Controllers;

/**
 * NOTE: Charts are not yet functional.
 *
 */
class ChartController extends Controller
{
    /**
     * Show chart.
     *
     */
    public function view()
    {
        $chart = new \App\Charts\ExchangeRateChart();
        $chart->labels(['One', 'Two', 'Three']);
        $chart->dataset('test', 'line', [1, 2, 3, 4]);
        return view('chart', compact('chart'));
    }
}
