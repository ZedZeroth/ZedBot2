<?php

namespace App\Http\Controllers\MultiDomain\Interfaces;

use Illuminate\View\View;

interface ViewerInterface
{
    /**
     * Show all instances of one model.
     *
     * @return View
     */
    public function showAll(): View;

    /**
     * Show the profile for a specific model instance.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View;
}
