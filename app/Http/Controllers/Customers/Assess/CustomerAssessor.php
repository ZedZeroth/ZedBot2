<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customers\Assess;

class CustomerAssessor
{
    /**
     * Updates all risk assessments for all customers.
     *
     * @param Collection $customers
     * @return bool
     */
    public function assess(
        \Illuminate\Support\Collection $customers
    ): bool {
        foreach ($customers as $customer) {
            $customer->assess();
        }
        return true;
    }
}
