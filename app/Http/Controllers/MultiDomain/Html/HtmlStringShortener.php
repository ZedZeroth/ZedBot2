<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Html;

/**
 * Shortens a string for viewability.
 */
class HtmlStringShortener
{
    /**
     * @param string $string
     * @param int $length
     * @return string
     */
    public function shorten(
        string $string,
        int $length
    ): string {
        // Validate $string
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: $string,
            stringName: 'string',
            charactersToRemove: [],
            shortestLength: 0,
            longestLength: pow(10, 3),
            mustHaveUppercase: false,
            canHaveUppercase: true,
            mustHaveLowercase: false,
            canHaveLowercase: true,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: false,
            isHexadecimal: false
        );

        // Validate lengthoriginatorName
        (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
            integer: $length,
            integerName: 'length',
            lowestValue: 5,
            highestValue: pow(10, 2)
        );

        $string = str_replace('’', '', $string);

        if (strlen($string) > $length) {
            return substr($string, 0, (int) ceil(($length - 3) / 2))
                . '...' . substr($string, (int) (-1 * floor(($length - 3) / 2)));
        } else {
            return $string;
        }
    }
}
