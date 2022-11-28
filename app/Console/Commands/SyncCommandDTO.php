<?php

declare(strict_types=1);

namespace App\Console\Commands;

/**
 * The command data transfer object
 * for moving command data between
 * sycnhronizer commands and their
 * controllers.
 */
class SyncCommandDTO implements CommandDTOInterface
{
    public function __construct(
        public string $api,
        public int $numberToFetch
    ) {
    }
}
