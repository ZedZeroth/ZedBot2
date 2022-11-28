<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\Synchronizer\Responses;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\MultiDomain\Money\MoneyConverter;
use App\Models\Currency;
use App\Http\Controllers\Accounts\AccountDTO;
use App\Http\Controllers\MultiDomain\Interfaces\ResponseAdapterInterface;

class AccountSynchronizerResponseAdapterForLCS0 implements
    ResponseAdapterInterface
{
    /**
     * Builds an array of model DTOs
     * from the responseBody.
     *
     * @param array $responseBody
     * @return array
     */
    public function buildDTOs(
        array $responseBody
    ): array {
        $accountDTOs = [];
        //Extract relevant data from the response
        $walletsWithBalance = [];
        /*💬*/ //print_r($responseBody);
        foreach (
            $responseBody['currencies'] as $currencyCode => $currencyArray
        ) {
            /*💬*/ //echo $currency . PHP_EOL;
            foreach ($currencyArray as $address => $element) {
                if (is_array($element)) {
                    if (array_key_exists('balance', $element)) {
                        if ($element['balance'] > 0) {
                            /*💬*/ //echo $currencyCode . PHP_EOL;
                            /*💬*/ //echo $key . PHP_EOL;
                            /*💬*/ //echo 'Balance: ' . $element['balance'] . PHP_EOL . PHP_EOL;

                            if ($currencyCode == 'BTC') {
                                $network = 'Bitcoin';
                            } elseif (
                                $currencyCode == 'ETH' or
                                str_contains($currencyCode, 'ERC20')
                            ) {
                                $network = 'Ethereum';
                            } elseif (
                                str_contains($currencyCode, 'BEP20')
                            ) {
                                $network = 'BSC';
                            } else {
                                $network = 'XXX';
                                Log::warn("{$currencyCode} has no assigned network!");
                            }

                            // Determine the currency
                            $currency = Currency::
                            where(
                                'code',
                                $currencyCode
                            )->firstOrFail();

                            // Convert amount to base units
                            $balance = (new MoneyConverter())
                            ->convert(
                                amount: $element['balance'],
                                currency: $currency
                            );

                            // ADAPT CURRENCY FOR SECOND BTC WALLET!

                            array_push(
                                $accountDTOs,
                                new AccountDTO(
                                    network: (string) $network,
                                    identifier: (string) 'fps'
                                        . '::' . strtolower($currencyCode)
                                        . '::' . $address,
                                    customer_id: (int) 0,
                                    networkAccountName: (string) '',
                                    label: (string) 'LCS ' . $currencyCode . ' wallet',
                                    currency_id: (int) $currency->id,
                                    balance: (int) $balance,
                                ),
                            );
                        }
                    }
                }
            }
        }

        return $accountDTOs;
    }
}
