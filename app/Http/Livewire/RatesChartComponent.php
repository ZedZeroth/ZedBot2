<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Livewire\Component;
use App\Charts\ExchangeRateChart;
use Illuminate\View\View;

/**
 * NOTE: Charts are not yet functional.
 *
 */
class RatesChartComponent extends Component
{
    protected ExchangeRateChart $chart;

    /**
     * Renders the view component.
     *
     * @return View
     */
    public function render(): View
    {
        $this->chart = new ExchangeRateChart();
        $this->chart->labels(['A', 'B', 'C', 'D']);
        $this->chart->dataset('test', 'line', [1, 2, 3, rand(0, 5)]);
        return view('livewire.rates-chart-component', [
            'chart' => $this->chart
        ]);
        $this->emit(
            'chartUpdate',
            $this->chart->id,
            $this->chart->labels(),
            $this->chart->datasets()
        );
    }
}
