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
            source: __FILE__ . ' (' . __LINE__ . ')',
            charactersToRemove: [],
            shortestLength: pow(10, 1),
            longestLength: pow(10, 2),
            mustHaveUppercase: true,
            canHaveUppercase: true,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: true,
            isHexadecimal: false
        );

        // Validate $apiSuffix
        (new ApiValidator())->validate(
            apiCode: $apiSuffix
        );

        $adapterMethods = get_class_methods($adapter);
        //var_dump($adapterMethods);
        foreach ($adapterMethods as $index => $method) {
            if (
                str_contains($method, 'mockery')
                or
                str_contains($method, '__')
                or
                str_contains($method, 'should')
                or
                str_contains($method, 'allows')
                or
                str_contains($method, 'expects')
                or
                str_contains($method, 'asUndefined')
                or
                str_contains($method, 'makePartial')
                or
                str_contains($method, 'byDefault')
            ) {
                unset($adapterMethods[$index]);
            }
        }
        //$adapterMethods = array_values($adapterMethods);
        //var_dump($adapterMethods);

        $prefix = '"' . $adapterName . '" adapter ';
        if (
            count(array_intersect($adapterMethods, $requiredMethods))
                != count($requiredMethods)
            or
            count($adapterMethods) != count($requiredMethods)
        ) {
            throw new AdapterValidationException(
                message: $prefix . '[' . implode(',', $adapterMethods) . '] does not contain these methods: ' . implode(',', $requiredMethods)
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
