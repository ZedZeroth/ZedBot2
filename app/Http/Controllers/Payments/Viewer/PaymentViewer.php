<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments\Viewer;

use Illuminate\View\View;
use App\Http\Controllers\MultiDomain\Interfaces\ViewerInterface;
use App\Http\Controllers\MultiDomain\Interfaces\NetworkViewerInterface;
use App\Models\Payment;
use App\Http\Controllers\MultiDomain\Html\HtmlModelTableBuilder;
use App\Http\Controllers\MultiDomain\Html\HtmlPaymentRowBuilder;

class PaymentViewer implements
    ViewerInterface,
    NetworkViewerInterface
{
    /**
     * Show all payments (on every network).
     *
     * @return View
     */
    public function showAll(): View
    {
        $payments = Payment::all()->sortByDesc('timestamp');

        if ($payments->count()) {
            $paymentsTable =
                (new HtmlPaymentRowBuilder())
                    ->build($payments);
        } else {
            $paymentsTable = 'No payments exist.';
        }

        return view('payments', [
            'payments' => $payments,
            'paymentsTable' => $paymentsTable
        ]);
    }

    /**
     * Show the profile for a specific payment.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View {

        //Verify payment exists
        $payment = Payment::where('id', $identifier)->firstOrFail();

        // Return the View
        return view('payment', [
            'payment' => $payment,
            'modelTable' =>
            (new HtmlModelTableBuilder())
                ->build($payment),
            'paymentTable' =>
                (new HtmlPaymentRowBuilder())
                    ->build(collect([$payment])),
        ]);
    }

    /**
     * Show all payment networks.
     *
     * @return View
     */
    public function showNetworks(): View
    {
        return view(
            'payment-networks',
            [
                'payments' => Payment::all()
                    ->unique('network')
            ]
        );
    }

    /**
     * Show all payments on one payment network.
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
            $html = 'No such network exists.';
        } else {
            $payments = Account::where('network', $network)->get();
            if ($payments->count()) {
                $html = (new HtmlAccountRowBuilder())
                    ->build($payments);
            } else {
                $html = 'No payments exist on this network.';
            }
        }

        // Return the View
        return view(
            'network-payments',
            [
                'network' => $network,
                'paymentsTable' => $html
            ]
        );
    }
}
