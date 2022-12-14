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
     * @param string $source
     * @param string $after
     * @param string $before
     * @return bool
     */
    public function validate(
        string $timestamp,
        string $timestampName,
        string $source,
        string $after,
        string $before,
    ): bool {
        // Validate $timestamp
        (new StringValidator())->validate(
            string: $timestamp,
            stringName: 'timestamp',
            source: __FILE__ . ' (' . __LINE__ . ')',
            charactersToRemove: ['-', 'T', ':', '.', '+'],
            shortestLength: 25,
            longestLength: 25,
            mustHaveUppercase: false,
            canHaveUppercase: false,
            mustHaveLowercase: false,
            canHaveLowercase: false,
            isAlphabetical: false,
            isNumeric: true,
            isAlphanumeric: true,
            isHexadecimal: false
        );

        // Validate $timestampName
        (new StringValidator())->validate(
            string: $timestampName,
            stringName: 'timestampName',
            source: __FILE__ . ' (' . __LINE__ . ')',
            charactersToRemove: [],
            shortestLength: 3,
            longestLength: 20,
            mustHaveUppercase: false,
            canHaveUppercase: true,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: true,
            isHexadecimal: false
        );

        // Validate $after
        (new StringValidator())->validate(
            string: $after,
            stringName: 'after',
            source: __FILE__ . ' (' . __LINE__ . ')',
            charactersToRemove: ['-', 'T', ':', '.', '+'],
            shortestLength: 25,
            longestLength: 25,
            mustHaveUppercase: false,
            canHaveUppercase: false,
            mustHaveLowercase: false,
            canHaveLowercase: false,
            isAlphabetical: false,
            isNumeric: true,
            isAlphanumeric: true,
            isHexadecimal: false
        );

        // Validate $before
        (new StringValidator())->validate(
            string: $before,
            stringName: 'before',
            source: __FILE__ . ' (' . __LINE__ . ')',
            charactersToRemove: ['-', 'T', ':', '.', '+'],
            shortestLength: 25,
            longestLength: 25,
            mustHaveUppercase: false,
            canHaveUppercase: false,
            mustHaveLowercase: false,
            canHaveLowercase: false,
            isAlphabetical: false,
            isNumeric: true,
            isAlphanumeric: true,
            isHexadecimal: false
        );

        $prefix = $source . ': "' . $timestampName . '" timestamp ';
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
