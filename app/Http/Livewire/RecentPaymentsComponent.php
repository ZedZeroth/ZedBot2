<?php

declare(strict_types=1);

namespace App\Http\Livewire;

class RecentPaymentsComponent extends \Livewire\Component
{
    public \Illuminate\Database\Eloquent\Collection $payments;

    /**
     * Renders the view component.
     *
     * @return View
     */
    public function render(): \Illuminate\View\View
    {
        $this->payments = \App\Models\Payment::all()->sortByDesc('timestamp')->take(10);

        return view('livewire.recent-payments-component');
    }
}
