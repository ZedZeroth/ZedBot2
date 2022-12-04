<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Html;

/**
 * Builds HTML table rows to display
 * information about a collection of
 * accounts.
 */
class HtmlAccountRowBuilder implements
    HtmlModelRowBuilderInterface
{
    /**
     * @param Collection $models
     * @return string
     */
    public function build(
        \Illuminate\Support\Collection $models
    ): string {
        $html = '<table>';
        foreach ($models as $account) {
            $html .= '<tr>';

            $html = $html
                . '<td><a href="/'
                . $account->network
                . '/accounts">'
                . $account->network
                . '</a></td>'

                . '<td><a href="/customer/'
                . $account->customer()->firstOrFail()->identifier
                . '">'
                . $account->customer()->firstOrFail()->identifier
                . '</a></td>'

                . '<td>“' . $account->label . '”</td>'

                . '<td>' . $account->networkAccountName . '</td>'

                . '<td><a href="/account/'
                . $account->identifier
                . '">'
                . $account->identifier
                . '</a></td>';

            $html .= '</tr>';
        }
        $html .= '</table>';

        return $html;
    }
}
