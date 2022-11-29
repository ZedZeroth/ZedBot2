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
            charactersToRemove: [],
            shortestLength: 4,
            longestLength: pow(10, 2),
            mustHaveUppercase: false,
            canHaveUppercase: true,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: true
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
            isAlphanumeric: true
        );

        // Set prefix
        $prefix = '"' . $addressName . '" string ';

        // Run further validation

        // Validate the network is listed
        if (!in_array($network, explode(',', env('ZED_NETWORK_LIST')))) {
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
        } else {
            return true;
        }
    }
}
