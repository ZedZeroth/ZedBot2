<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use App\Models\Currency;
use Illuminate\View\View;

class CurrencyPopulatorComponent extends Component
{
    public $currencies;

    /**
     * Calls the 'currencies:populate' command.
     *
     * @return void
     */
    public function populate(): void
    {
        Artisan::call('currencies:populate browser');
    }

    /**
     * Renders the view component.
     *
     * @return View
     */
    public function render(): View
    {
        $this->currencies = Currency::all();
        return view('livewire.currency-populator-component');
    }
}
