<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Validators;

use App\Http\Controllers\MultiDomain\Interfaces\GeneralAdapterInterface;

class AdapterValidator
{
    /**
     * Checks an based on various conditions.
     *
     * @param GeneralAdapterInterface $adapter
     * @param string $adapterName
     * @param array $requiredMethods
     * @param string $platformSuffix
     * @return bool
     */
    public function validate(
        GeneralAdapterInterface $adapter,
        string $adapterName,
        array $requiredMethods,
        string $platformSuffix
    ): bool {
        $prefix = '"' . $adapterName . '" adapter ';
        if (count(array_intersect(get_class_methods($adapter), $requiredMethods)) != count($requiredMethods)) {
            throw new AdapterValidationException(
                message: $prefix . 'does not contain these methods: ' . implode(',', $requiredMethods)
            );
        } elseif (substr(get_class($adapter), -1*strlen($platformSuffix)) != $platformSuffix) {
            throw new AdapterValidationException(
                message: $prefix . 'is not an adapter for the ' . $platformSuffix. ' platform'
            );
        } else {
            return true;
        }
    }
}
