<?php

declare(strict_types=1);

namespace App\Http\Livewire;

class AccountSynchronizerComponent extends \Livewire\Component
{
    public \Illuminate\Database\Eloquent\Collection $accounts;
    public string $numberToFetch = '10';

    /**
     * Calls the 'accounts:sync' command.
     * @param string $api
     */
    public function sync(string $api): void
    {

        // Validate $api
        (new \App\Http\Controllers\MultiDomain\Validators\ApiValidator())
                ->validate(apiCode: $api);

        // Validate $this->numberToFetch
        (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
            integer: $this->numberToFetch,
            integerName: '$this->numberToFetch',
            lowestValue: 1,
            highestValue: pow(10, 5)
        );

        // Run the command
        \Illuminate\Support\Facades\Artisan::call(
            'accounts:sync browser '
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
        $this->accounts = \App\Models\Account::all();
        return view('livewire.account-synchronizer-component');
    }
}
