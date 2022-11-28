<?php

declare(strict_types=1);

namespace App\Http\Controllers\Html\MultiDomain;

/**
 * Builds HTML table rows to display
 * information about a collection of
 * models.
 */
interface HtmlCollectionRowBuilderInterface
{
    /**
     * @param Collection $models
     * @return string
     */
    public function build(
        \Illuminate\Support\Collection $models
    ): string;
}
