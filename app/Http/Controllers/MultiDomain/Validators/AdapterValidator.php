<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Validators;

class AdapterValidator
{
    /**
     * Checks an adapter based on various conditions.
     *
     * @param AdapterInterface $adapter
     * @param string $adapterName
     * @param array $requiredMethods
     * @param string $apiSuffix
     * @return bool
     */
    public function validate(
        \App\Http\Controllers\MultiDomain\Interfaces\AdapterInterface $adapter,
        string $adapterName,
        array $requiredMethods,
        string $apiSuffix
    ): bool {
        // Validate $adapterName
        (new StringValidator())->validate(
            string: $adapterName,
            stringName: 'adapterName',
            charactersToRemove: [],
            shortestLength: pow(10, 1),
            longestLength: pow(10, 2),
            mustHaveUppercase: true,
            canHaveUppercase: true,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: true
        );

        // Validate $apiSuffix
        (new ApiValidator())->validate(
            apiCode: $apiSuffix
        );

        $prefix = '"' . $adapterName . '" adapter ';
        if (
            count(array_intersect(get_class_methods($adapter), $requiredMethods))
                != count($requiredMethods)
            or
            count(get_class_methods($adapter)) != count($requiredMethods)
        ) {
            throw new AdapterValidationException(
                message: $prefix . 'does not contain these methods: ' . implode(',', $requiredMethods)
            );
        } elseif (substr(get_class($adapter), -1 * strlen($apiSuffix)) != $apiSuffix) {
            throw new AdapterValidationException(
                message: $prefix . 'is not an adapter for the ' . $apiSuffix . ' platform'
            );
        } else {
            return true;
        }
    }
}
