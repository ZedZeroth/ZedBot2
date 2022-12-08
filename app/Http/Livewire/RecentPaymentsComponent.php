<?php

declare(strict_types=1);

namespace App\Http\Livewire;

class RecentPaymentsComponent extends \Livewire\Component
{
    public string $paymentTable;

    /**
     * Renders the view component.
     *
     * @return View
     */
    public function render(): \Illuminate\View\View
    {
        $payments = \App\Models\Payment::all()->sortByDesc('timestamp')->take(10);

        if ($payments->count()) {
            $this->paymentsTable =
                (new \App\Http\Controllers\MultiDomain\Html\HtmlPaymentRowBuilder())
                    ->build($payments);
        } else {
            $this->paymentsTable = 'No payments exist.';
        }

        return view('livewire.recent-payments-component');
    }
}
