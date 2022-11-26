<?php

namespace App\Http\Controllers\Payments\Synchronizer\Responses;

use Illuminate\Support\Facades\Log;
use App\Models\Account;
use App\Http\Controllers\Accounts\AccountDTO;
use App\Http\Controllers\Accounts\AccountSynchronizer;
use App\Http\Controllers\Payments\PaymentDTO;
use App\Models\Currency;
use App\Http\Controllers\MultiDomain\Money\MoneyConverter;
use App\Http\Controllers\MultiDomain\Interfaces\ResponseAdapterInterface;

class PaymentsSynchronizerResponseAdapterForENM implements
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

            // Shift focus to 'payload' element
            $result = $result['payload'];

            // Determine the currency
            $currency = Currency::
                    where(
                        'code',
                        $result['CurrencyCode']
                    )->firstOrFail();

            // Determine beneficiary / originator details
            if ($result['DebitCreditCode'] == 'Credit') {
                // Originator
                $originatorAccountIdentifier =
                    $this->convertIbanToAccountIdentifier(
                        $result['CounterpartAccount_Iban']
                    );
                $originatorNetworkAccountName =
                    $result['CounterpartAccount_TransactionOwnerName'];
                $originatorLabel = '';

                //Beneficiary
                $beneficiaryAccountIdentifier =
                    $this->convertIbanToAccountIdentifier(
                        $result['Account_Iban']
                    );
                $beneficiaryNetworkAccountName = '';
                $beneficiaryLabel =
                    $result['Account_OwnerName'];
            } elseif ($result['DebitCreditCode'] == 'Debit') {
                // Originator
                $originatorAccountIdentifier =
                    $this->convertIbanToAccountIdentifier(
                        $result['Account_Iban']
                    );
                $originatorNetworkAccountName = '';
                $originatorLabel =
                    $result['Account_OwnerName'];

                //Beneficiary
                $beneficiaryAccountIdentifier =
                    $this->convertIbanToAccountIdentifier(
                        $result['CounterpartAccount_Iban']
                    );
                $beneficiaryNetworkAccountName = '';
                $beneficiaryLabel =
                    $result['CounterpartAccount_TransactionOwnerName'];
            } else {
                // Throw unexpected response error
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
                    label: $beneficiaryLabel,
                    currency_id: $currency->id,
                    balance: 0
                )
            );

            // Sync the account DTOs
            (new AccountSynchronizer())
                ->sync(DTOs: $accountDTOs);

            // Convert amount to base units
            $amount = (new MoneyConverter())
            ->convert(
                amount: $result['Amount'],
                currency: $currency
            );

            array_push(
                $paymentDTOs,
                new PaymentDTO(
                    network: (string) 'FPS',
                    identifier: (string) 'enm::'
                        . $result['Id'],
                    amount: (int) $amount,
                    currency_id: (int) $currency->id,
                    originator_id: (int) Account::
                        where('identifier', $originatorAccountIdentifier)
                        ->first()->id,
                    beneficiary_id: (int) Account::
                        where('identifier', $beneficiaryAccountIdentifier)
                        ->first()->id,
                    memo: (string) $result['Reference'],
                    timestamp: (string) date(
                        'Y-m-d H:i:s',
                        strtotime($result['SettledTime'])
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
