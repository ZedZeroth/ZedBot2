<?php

declare(strict_types=1);

namespace App\Http\Livewire;

class HeldPaymentsComponent extends \Livewire\Component
{
    public \Illuminate\Database\Eloquent\Collection $payments;
    public int $paymentCount;

    /**
     * Renders the view component.
     *
     * @return View
     */
    public function render(): \Illuminate\View\View
    {
        $this->payments = \App\Models\Payment::where(
            'state',
            'App\Models\Payments\States\Held'
        )->get()->sortByDesc('timestamp');

        $this->paymentCount = $this->payments->count();

        return view('livewire.held-payments-component');
    }
}
