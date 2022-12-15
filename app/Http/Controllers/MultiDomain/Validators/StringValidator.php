<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Validators;

class StringValidator
{
    /**
     * Checks a string based on various conditions.
     *
     * @param string $string
     * @param string $stringName
     * @param string $source
     * @param array $charactersToRemove
     * @param int $shortestLength
     * @param int $longestLength
     * @param bool $mustHaveUppercase
     * @param bool $canHaveUppercase
     * @param bool $mustHaveLowercase
     * @param bool $canHaveLowercase
     * @param bool $isAlphabetical
     * @param bool $isNumeric
     * @param bool $isAlphanumeric
     * @param bool $isHexadecimal
     * @return bool
     */
    public function validate(
        string $string,
        string $stringName,
        string $source,
        array $charactersToRemove,
        int $shortestLength,
        int $longestLength,
        bool $mustHaveUppercase,
        bool $canHaveUppercase,
        bool $mustHaveLowercase,
        bool $canHaveLowercase,
        bool $isAlphabetical,
        bool $isNumeric,
        bool $isAlphanumeric,
        bool $isHexadecimal
    ): bool {
        // Validate shortestLength
        (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
            integer: $shortestLength,
            integerName: 'shortestLength',
            lowestValue: 0,
            highestValue: pow(10, 2)
        );

        // Validate longestLength
        (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
            integer: $longestLength,
            integerName: 'longestLength',
            lowestValue: 0,
            highestValue: pow(10, 4)
        );

        // Set prefix
        $prefix = $source . ': "' . $stringName . '" string ';

        // Remove unwated characters
        $string = str_replace($charactersToRemove, '', $string);

        // Run validation
        if (strlen($string) < $shortestLength) {
            throw new StringValidationException(
                message: $prefix . '"' . $string . '" (' . strlen($string) . ')'
                . ' is shorter than ' . $shortestLength . ' characters'
            );
        } elseif (strlen($string) > $longestLength) {
            throw new StringValidationException(
                message: $prefix . '"' . $string . '" (' . strlen($string) . ')'
                . ' is longer than ' . $longestLength . ' characters'
            );
        } elseif (
            $mustHaveUppercase and
            $string == strtolower($string)
        ) {
            throw new StringValidationException(
                message: $prefix . '"' . $string . '" must have uppercase characters'
            );
        } elseif (
            !$canHaveUppercase and
            $string != strtolower($string)
        ) {
            throw new StringValidationException(
                message: $prefix . '"' . $string . '" cannot have uppercase characters'
            );
        } elseif (
            $mustHaveLowercase and
            $string == strtoupper($string)
        ) {
            throw new StringValidationException(
                message: $prefix . '"' . $string . '" must have lowercase characters'
            );
        } elseif (
            !$canHaveLowercase and
            $string != strtoupper($string)
        ) {
            throw new StringValidationException(
                message: $prefix . '"' . $string . '" cannot have lowercase characters'
            );
        } elseif ($string and $isAlphabetical and !ctype_alpha($string)) {
            throw new StringValidationException(
                message: $prefix . '"' . $string . '" is not alphabetical'
            );
        } elseif ($string and $isNumeric and !is_numeric($string)) {
            throw new StringValidationException(
                message: $prefix . '"' . $string . '" is not numerical'
            );
        } elseif ($string and $isAlphanumeric and !ctype_alnum($string)) {
            throw new StringValidationException(
                message: $prefix . '"' . $string . '" is not alphanumeric'
            );
        } elseif ($string and $isHexadecimal and !ctype_xdigit($string)) {
            throw new StringValidationException(
                message: $prefix . '"' . $string . '" is not hexadecimal'
            );
        } else {
            return true;
        }
    }
}
