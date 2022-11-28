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
     * @param int $shortestLength
     * @param int $longestLength
     * @param bool $containsUppercase
     * @param bool $containsLowercase
     * @param bool $isAlphabetical
     * @param bool $isNumeric
     * @param bool $isAlphanumeric
     * @return bool
     */
    public function validate(
        string $string,
        string $stringName,
        int $shortestLength,
        int $longestLength,
        bool $containsUppercase,
        bool $containsLowercase,
        bool $isAlphabetical,
        bool $isNumeric,
        bool $isAlphanumeric
    ): bool {
        $prefix = '"' . $stringName . '" string ';
        if (strlen($string) < $shortestLength) {
            throw new StringValidationException(
                message: $prefix . 'is shorter than ' . $shortestLength . ' characters'
            );
        } elseif (strlen($string) > $longestLength) {
            throw new StringValidationException(
                message: $prefix . 'is longer than ' . $longestLength . ' characters'
            );
        } elseif ($containsUppercase and $string == strtolower($string)) {
            throw new StringValidationException(message: $prefix . 'contains no uppercase characters');
        } elseif (!$containsUppercase and $string != strtolower($string)) {
            throw new StringValidationException(message: $prefix . 'contains uppercase characters');
        } elseif ($containsLowercase and $string == strtoupper($string)) {
            throw new StringValidationException(message: $prefix . 'contains no lowercase characters');
        } elseif (!$containsLowercase and $string != strtoupper($string)) {
            throw new StringValidationException(message: $prefix . 'contains lowercase characters');
        } elseif ($isAlphabetical and !ctype_alpha($string)) {
            throw new StringValidationException(message: $prefix . 'is not alphabetical');
        } elseif ($isNumeric and !is_numeric($string)) {
            throw new StringValidationException(message: $prefix . 'is not numerical');
        } elseif ($isAlphanumeric and !ctype_alnum($string)) {
            throw new StringValidationException(message: $prefix . 'is not alphanumeric');
        } else {
            return true;
        }
    }
}
