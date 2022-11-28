<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments\Synchronizer\Responses;

use Illuminate\Support\Facades\Log;
use App\Models\Account;
use App\Http\Controllers\Accounts\AccountDTO;
use App\Http\Controllers\Accounts\AccountSynchronizer;
use App\Http\Controllers\Payments\PaymentDTO;
use App\Models\Currency;
use App\Http\Controllers\MultiDomain\Money\MoneyConverter;
use App\Http\Controllers\MultiDomain\Interfaces\ResponseAdapterInterface;

class PaymentsSynchronizerResponseAdapterForENMF implements
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
        $paymentDTOs = [];
        /*💬*/ //print_r($responseBody);
        foreach (
            $responseBody['results'] as $result
        ) {
            /*💬*/ //print_r($result);

            // Determine the currency
            $currency = Currency::
                    where(
                        'code',
                        $result['transactionCurrency']
                    )->firstOrFail();

            // Determine beneficiary / originator
            $beneficiary = explode(', ', $result['counterparty']);
            $beneficiarylabel = $beneficiary[0];
            $beneficiaryAccountIdentifier =
                $this->convertIbanToAccountIdentifier($beneficiary[1]);

            if ($beneficiarylabel == env('ZED_ENM_ACCOUNT_NAME')) {
                $originator = explode(', ', $result['counterparty']);
                $originatorNetworkAccountName = $originator[0];
                $originatorLabel = '';
                $originatorAccountIdentifier =
                    $this->convertIbanToAccountIdentifier($originator[1]);
            } else {
                $originatorNetworkAccountName = '';
                $originatorLabel = env('ZED_ENM_ACCOUNT_NAME');
                $originatorAccountIdentifier =
                    $this->convertIbanToAccountIdentifier($result['accno']);
            }

            $accountDTOs = [];
            // Create the originator DTO
            array_push(
                $accountDTOs,
                new AccountDTO(
                    network: (string) 'FPS',
                    identifier: (string) $originatorAccountIdentifier,
                    customer_id: 0,
                    networkAccountName: $originatorNetworkAccountName,
                    label: $originatorLabel,
                    currency_id: $currency->id,
                    balance: 0
                )
            );

            // Create the beneficiary DTO
            array_push(
                $accountDTOs,
                new AccountDTO(
                    network: (string) 'FPS',
                    identifier: (string) $beneficiaryAccountIdentifier,
                    customer_id: 0,
                    networkAccountName: '',
                    label: $beneficiarylabel,
                    currency_id: $currency->id,
                    balance: 0
                )
            );

            // Sync accounts
            (new AccountSynchronizer())
                ->sync(DTOs: $accountDTOs);

            // Convert amount to base units
            $amount = (new MoneyConverter())
            ->convert(
                amount: abs($result['transactionAmount']),
                currency: $currency
            );

            array_push(
                $paymentDTOs,
                new PaymentDTO(
                    network: (string) 'FPS',
                    identifier: (string) 'enm::'
                        . $result['id'],
                    amount: (int) $amount,
                    currency_id: (int) $currency->id,
                    originator_id: (int) Account::
                        where('identifier', $originatorAccountIdentifier)
                        ->first()->id,
                    beneficiary_id: (int) Account::
                        where('identifier', $beneficiaryAccountIdentifier)
                        ->first()->id,
                    memo: (string) $result['paymentReference'],
                    timestamp: (string) date(
                        'Y-m-d H:i:s',
                        strtotime($result['transactionTimeLocal'])
                    ),
                )
            );
        }

        // Return the payment DTOs
        return $paymentDTOs;
    }

    /**
     * Convert an IBAN into this system's
     * FPS account identifier.
     *
     * @param string $iban
     * @return string
     */
    public function convertIbanToAccountIdentifier(
        string $iban
    ): string {
        return 'fps'
            . '::' . 'gbp'
            . '::' . substr($iban, -14, 6) // Sort code
            . '::' . substr($iban, -8); // Account number
    }
}
