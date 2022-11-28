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
        // Validate $api
        (new \App\Http\Controllers\MultiDomain\Validators\APIValidator())
                ->validate(apiCode: $api);

        // Validate $this->numberToFetch
        (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
            integer: (int) $this->numberToFetch,
            integerName: '$this->numberToFetch',
            lowestValue: 1,
            highestValue: pow(10, 5)
        );

        // Run the command
        \Illuminate\Support\Facades\Artisan::call(
            'payments:sync browser '
            . $api
            . ' '
            . $this->numberToFetch
        );
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
