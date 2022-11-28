<?php

declare(strict_types=1);

namespace App\Http\Livewire;

class PaymentSynchronizerComponent extends \Livewire\Component
{
    public \Illuminate\Database\Eloquent\Collection $payments;
    public string $numberToFetch = '10';

    /**
     * Calls the 'payments:sync' command.
     *
     * @param string $api
     * @return void
     */
    public function sync(string $api): void
    {
        try {
            \Illuminate\Support\Facades\Artisan::call(
                'payments:sync browser '
                . $api
                . ' '
                . $this->numberToFetch
            );
        } catch (\Symfony\Component\Console\Exception\RuntimeException $e) {
            Log::error(__METHOD__ . ' [' . __LINE__ . '] ' . $e->getMessage());
        }
    }

    /**
     * Renders the view component.
     *
     * @return View
     */
    public function render(): \Illuminate\View\View
    {
        $this->payments = \App\Models\Payment::all();
        return view('livewire.payment-synchronizer-component');
    }
}
