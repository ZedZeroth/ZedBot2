<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Interfaces;

use Illuminate\View\View;
use App\Console\Commands\SyncCommandDTO;

interface ControllerInterface
{
    /**
     * Show all models (on every network).
     *
     * @return View
     */
    public function showAll(): View;

    /**
     * Show the profile for a specific model.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View;

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

    /**
     * Fetches models from external APIs
     * and creates any new ones that do not exist.
     *
     * @param SyncCommandDTO $dto
     * @return void
     */
    public function sync(
        SyncCommandDTO $commandDTO
    ): void;
}
