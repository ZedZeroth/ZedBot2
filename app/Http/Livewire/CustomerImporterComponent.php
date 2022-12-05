<?php

declare(strict_types=1);

namespace App\Http\Livewire;

class CustomerImporterComponent extends \Livewire\Component
{
    public $customers;

    /**
     * Calls the 'customers:import' command.
     *
     * @return void
     */
    public function import(): void
    {
        \Illuminate\Support\Facades\Artisan::call(
            'customers:import browser'
        );
    }

    /**
     * Renders the view component.
     *
     * @return View
     */
    public function render(): \Illuminate\View\View
    {
        $this->customers = \App\Models\Customer::all();
        return view('livewire.customer-importer-component');
    }
}
