<?php

declare(strict_types=1);

namespace App\Http\Livewire;

class CurrencyPopulatorComponent extends \Livewire\Component
{
    public $currencies;

    /**
     * Calls the 'currencies:populate' command.
     *
     * @return void
     */
    public function populate(): void
    {
        \Illuminate\Support\Facades\Artisan::call('currencies:populate browser');
    }

    /**
     * Renders the view component.
     *
     * @return View
     */
    public function render(): \Illuminate\View\View
    {
        $this->currencies = \App\Models\Currency::all();
        return view('livewire.currency-populator-component');
    }
}
