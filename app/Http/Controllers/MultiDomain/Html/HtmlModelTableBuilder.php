<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Html;

/**
 * Builds an HTML table to display
 * information about a model.
 */
class HtmlModelTableBuilder
{
    /**
     * @param object $model
     * @return string
     */
    public function build(
        object $model
    ): string {
        $html = '<table>';
        foreach ($model->toArray() as $property => $value) {
            $html .= '<tr><td style="text-align: right; font-weight: bold;">'
                . $property
                . ': </td><td>'
                . $value
                . '</td></tr>';
        }
        $html .= '</table>';
        return $html;
    }
}
