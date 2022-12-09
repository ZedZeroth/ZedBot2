<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Validators;

class BlockchainAddressValidator
{
    /**
     * Checks a blockchain address is valid.
     *
     * @param string $address
     * @param string $addressName
     * @param string $network
     * @return bool
     */
    public function validate(
        string $address,
        string $addressName,
        string $network
    ): bool {
        // Validate $addressName
        (new StringValidator())->validate(
            string: $addressName,
            stringName: 'addressName',
            charactersToRemove: [' ', '-', '’'],
            shortestLength: 4,
            longestLength: pow(10, 2),
            mustHaveUppercase: false,
            canHaveUppercase: true,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: true,
            isHexadecimal: false
        );

        // Validate $address as a string
        (new StringValidator())->validate(
            string: $address,
            stringName: 'address',
            charactersToRemove: [],
            shortestLength: 14,
            longestLength: 74,
            mustHaveUppercase: false,
            canHaveUppercase: true,
            mustHaveLowercase: false,
            canHaveLowercase: true,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: true,
            isHexadecimal: false
        );

        // Set prefix
        $prefix = '"' . $addressName . '" address "' . $address . '" ';

        // Run further validation

        // Validate the network is listed
        if (!in_array($network, config('app.ZED_NETWORK_LIST'))) {
            throw new BlockchainAddressValidationException(
                message: $prefix . 'is not in the network list'
            );
        // Validate the address
        } elseif (
            $network == 'Bitcoin'
            and
            !(new \Kielabokkie\Bitcoin\AddressValidator())
                ->isValid($address)
        ) {
            throw new BlockchainAddressValidationException(
                message: $prefix . 'is not a valid bitcoin address'
            );
        } elseif (
            $network == 'Tron'
            and
            !$this->validateTron(address: $address, prefix: $prefix)
        ) {
            // Exceptions thrown in validation method.
        } else {
            return true;
        }
    }

    /**
     * Checks a Tron address is valid.
     *
     * @param string $address
     * @param string $prefix
     * @return array
     */
    public function validateTron(
        string $address,
        string $prefix
    ): bool {
        $valid = false;
        $response = \Illuminate\Support\Facades\Http::connectTimeout(
            (int) config('app.ZED_CONNECT_SINGLE_TIMEOUT')
        )
            ->retry((int) config('app.ZED_CONNECT_RETRY'), 1000)
            ->timeout((int) config('app.ZED_CONNECT_ABSOLUTE_TIMEOUT'))
            ->get(
                'https://api.shasta.trongrid.io/wallet/validateaddress?address='
                . $address
            );

        $statusCode = $response->status();
        $responseArray = json_decode(
            (string) $response->getBody(),
            true
        );

        //If valid then return response
        if ($statusCode == 200) {
            if ($responseArray['result']) {
                $valid = true;
            } else {
                throw new BlockchainAddressValidationException(
                    message: $prefix . 'is not a valid Tron address ('
                        . $responseArray['message'] . ')'
                );
            }
        // If invalid then return an empty array
        } else {
            throw new BlockchainAddressValidationException(
                message: 'Error connecting to api.shasta.trongrid.io/wallet/validateaddress service'
            );
        }
        return $valid;
    }
}
