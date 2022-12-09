<?php

declare(strict_types=1);

namespace App\Http\Livewire;

class HeldPaymentsComponent extends \Livewire\Component
{
    public string $paymentTable;
    public int $paymentCount;

    /**
     * Renders the view component.
     *
     * @return View
     */
    public function render(): \Illuminate\View\View
    {
        $payments = \App\Models\Payment::where(
            'state',
            'App\Models\Payments\States\Held'
        )->get()->sortByDesc('timestamp');

        $this->paymentCount = $payments->count();

        if ($payments->count()) {
            $this->paymentsTable =
                (new \App\Http\Controllers\MultiDomain\Html\HtmlPaymentRowBuilder())
                    ->build($payments);
        } else {
            $this->paymentsTable = 'No held payments.';
        }

        return view('livewire.held-payments-component');
    }
}
