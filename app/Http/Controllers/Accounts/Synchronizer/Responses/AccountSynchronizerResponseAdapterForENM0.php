<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\Synchronizer\Responses;

use App\Http\Controllers\MultiDomain\Validators\ArrayValidator;

class AccountSynchronizerResponseAdapterForENM0 implements
    \App\Http\Controllers\MultiDomain\Interfaces\ResponseAdapterInterface,
    \App\Http\Controllers\MultiDomain\Interfaces\AdapterInterface
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
        /*💬*/ //print_r($responseBody);

        // Validate the injected array
        (new ArrayValidator())->validate(
            array: $responseBody,
            arrayName: 'responseBody',
            requiredKeys: ['count', 'results']
        );

        // Adapt each account
        $accountDTOs = [];
        foreach ($responseBody['results'] as $result) {
            /*💬*/ //print_r($result);

            // Validate the injected array
            (new ArrayValidator())->validate(
                array: $result,
                arrayName: 'result',
                requiredKeys: [
                    'ern',
                    'nickName',
                    'beneficiaryERN',
                    'confidenceScore',
                    'riskWeight',
                    'sortCode',
                    'accountNumber',
                    'accountName',
                    'createdDate',
                    'owners',
                    'hasOwners'
                ]
            );

            // Determine the currency
            $currency = \App\Models\Currency::
                    where(
                        'code',
                        'GBP'
                    )->firstOrFail();

            // Create the DTO
            array_push(
                $accountDTOs,
                new \App\Http\Controllers\Accounts\AccountDTO(
                    network: (string) 'FPS',
                    identifier: (string) 'fps'
                        . '::' . 'gbp'
                        . '::' . $result['sortCode']
                        . '::' . $result['accountNumber'],
                    customer_id: (int) 0,
                    networkAccountName: (string) '',
                    label: (string) $result['accountName'],
                    currency_id: (int) $currency->id,
                    balance: (int) 0,
                ),
            );
        }

        return $accountDTOs;
    }
}
