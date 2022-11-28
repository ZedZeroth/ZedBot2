<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Payment;
use Illuminate\View\View;

class PaymentSynchronizerComponent extends Component
{
    public Collection $payments;
    public string $numberToFetch = '10';

    /**
     * Calls the 'payments:sync' command.
     *
     * @param string $provider
     * @return void
     */
    public function sync(string $provider): void
    {
        try {
            Artisan::call(
                'payments:sync browser '
                . $provider
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
    public function render(): View
    {
        $this->payments = Payment::all();
        return view('livewire.payment-synchronizer-component');
    }
}
