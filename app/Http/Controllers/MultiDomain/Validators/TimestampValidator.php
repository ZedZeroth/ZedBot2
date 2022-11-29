<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Validators;

class TimestampValidator
{
    /**
     * Checks a timestamp based on various conditions.
     *
     * @param string $timestamp
     * @param string $timestampName
     * @param string $after
     * @param string $before
     * @return bool
     */
    public function validate(
        string $timestamp,
        string $timestampName,
        string $after,
        string $before,
    ): bool {
        // Validate $timestamp
        (new StringValidator())->validate(
            string: $timestamp,
            stringName: 'timestamp',
            charactersToRemove: ['-', 'T', ':', '.', '+'],
            shortestLength: 25,
            longestLength: 25,
            mustHaveUppercase: false,
            canHaveUppercase: false,
            mustHaveLowercase: false,
            canHaveLowercase: false,
            isAlphabetical: false,
            isNumeric: true,
            isAlphanumeric: true
        );

        // Validate $timestampName
        (new StringValidator())->validate(
            string: $timestampName,
            stringName: 'timestampName',
            charactersToRemove: [],
            shortestLength: 3,
            longestLength: 20,
            mustHaveUppercase: false,
            canHaveUppercase: true,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: true
        );

        // Validate $after
        (new StringValidator())->validate(
            string: $after,
            stringName: 'after',
            charactersToRemove: ['-', 'T', ':', '.', '+'],
            shortestLength: 25,
            longestLength: 25,
            mustHaveUppercase: false,
            canHaveUppercase: false,
            mustHaveLowercase: false,
            canHaveLowercase: false,
            isAlphabetical: false,
            isNumeric: true,
            isAlphanumeric: true
        );

        // Validate $before
        (new StringValidator())->validate(
            string: $before,
            stringName: 'before',
            charactersToRemove: ['-', 'T', ':', '.', '+'],
            shortestLength: 25,
            longestLength: 25,
            mustHaveUppercase: false,
            canHaveUppercase: false,
            mustHaveLowercase: false,
            canHaveLowercase: false,
            isAlphabetical: false,
            isNumeric: true,
            isAlphanumeric: true
        );

        $prefix = '"' . $timestampName . '" timestamp ';
        $datetime = strtotime($timestamp);
        if (!is_numeric($datetime)) {
            throw new TimestampValidationException(
                message: $prefix . 'is not a valid timestamp'
            );
        } elseif ($datetime < strtotime($after)) {
            throw new TimestampValidationException(
                message: $prefix . 'is before ' . $after
            );
        } elseif ($datetime > strtotime($before)) {
            throw new TimestampValidationException(
                message: $prefix . 'is after ' . $before
            );
        } else {
            return true;
        }
    }
}
