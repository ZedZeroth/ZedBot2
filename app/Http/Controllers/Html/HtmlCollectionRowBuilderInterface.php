<?php

declare(strict_types=1);

namespace App\Http\Controllers\Html;

use Illuminate\Database\Eloquent\Collection;

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
        Collection $models
    ): string;
}
