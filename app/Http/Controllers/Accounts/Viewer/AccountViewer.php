<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\Viewer;

use Illuminate\View\View;
use App\Http\Controllers\MultiDomain\Interfaces\ViewerInterface;
use App\Http\Controllers\MultiDomain\Interfaces\NetworkViewerInterface;
use App\Models\Account;
use App\Http\Controllers\MultiDomain\Html\HtmlModelTableBuilder;
use App\Http\Controllers\MultiDomain\Html\HtmlPaymentRowBuilder;
use App\Http\Controllers\MultiDomain\Html\HtmlAccountRowBuilder;

class AccountViewer implements
    ViewerInterface,
    NetworkViewerInterface
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
            'accounts' => $accounts,
            'accountsTable' =>
                (new HtmlAccountRowBuilder())
                    ->build($accounts)
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
        $account = Account::where('identifier', $identifier)->firstOrFail();
        return view('account', [
            'account' => $account,
            'modelTable' =>
            (new HtmlModelTableBuilder())
                ->build($account),
            'creditsTable' =>
                (new HtmlPaymentRowBuilder())
                    ->build($account->credits()->get()),
            'debitsTable' =>
                (new HtmlPaymentRowBuilder())
                    ->build($account->debits()->get()),
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
        $accounts = Account::where('network', $network)->get();
        // Abort if no matches found
        if (empty($accounts->count())) {
            abort(404);
        }
        return view(
            'network-accounts',
            [
                'network' => $network,
                'accountsTable' =>
                    (new HtmlAccountRowBuilder())
                        ->build($accounts)
            ]
        );
    }
}
