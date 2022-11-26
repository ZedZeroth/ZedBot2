<?php

namespace App\Http\Controllers\MultiDomain\Interfaces;

use Illuminate\View\View;

interface NetworkViewerInterface
{
    /**
     * Show all model networks.
     *
     * @return View
     */
    public function showNetworks(): View;

    /**
     * Show all models on one network.
     *
     * @param string $network
     * @return View
     */
    public function showOnNetwork(
        string $network
    ): View;
}
