<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\View;

use Illuminate\View\View;
use App\Models\Account;
use App\Http\Controllers\MultiDomain\Html\HtmlPaymentRowBuilder;
use App\Http\Controllers\MultiDomain\Html\HtmlAccountRowBuilder;

class AccountViewer implements
    \App\Http\Controllers\MultiDomain\Interfaces\ViewerInterface,
    \App\Http\Controllers\MultiDomain\Interfaces\NetworkViewerInterface
{
    /**
     * Show all accounts (on every network).
     *
     * @return View
     */
    public function showAll(): View
    {
        $accounts = Account::all()->sortBy('identifier');

        return view('accounts', [
            'accounts' => $accounts
        ]);
    }

    /**
     * Show the profile for a specific account.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View {

        // Verify account exists
        $account = Account::where('identifier', $identifier)->firstOrFail();

        // Return the View
        return view('account', [
            'account' => $account,
            'modelTable' =>
            (new \App\Http\Controllers\MultiDomain\Html\HtmlModelTableBuilder())
                ->build($account),
        ]);
    }

    /**
     * Show all account networks.
     *
     * @return View
     */
    public function showNetworks(): View
    {
        return view(
            'account-networks',
            [
                'accounts' => Account::all()
                    ->unique('network')
            ]
        );
    }

    /**
     * Show all accounts on one account network.
     *
     * @param string $network
     * @return View
     */
    public function showOnNetwork(
        string $network
    ): View {
        // Sanitize string
        $network = preg_replace("/[^A-Za-z0-9]/", '', $network);

        // Verify network
        if (!in_array($network, config('app.ZED_NETWORK_LIST'))) {
            throw new \Exception('No such network exists.');
        }

        // Return the View
        return view(
            'network-accounts',
            [
                'network' => $network,
                'accounts' => Account::where('network', $network)->get()
            ]
        );
    }
}
