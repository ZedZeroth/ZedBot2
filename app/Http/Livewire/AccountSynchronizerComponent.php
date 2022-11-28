<?php

declare(strict_types=1);

namespace App\Http\Livewire;

class AccountSynchronizerComponent extends \Livewire\Component
{
    public \Illuminate\Database\Eloquent\Collection $accounts;
    public string $numberToFetch = '10';

    /**
     * Calls the 'accounts:sync' command.
     *
     */
    public function sync(string $api): void
    {
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
