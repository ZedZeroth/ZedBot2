<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Html;

/**
 * Builds HTML table rows to display
 * information about a collection of
 * accounts.
 */
class HtmlAccountRowBuilder
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
            $holderHTML = '<td style="font-style: italic;">'
                . 'NO HOLDER'
                . '</td>';
            if ($account->customer) {
                $holderHTML = '<td>'
                    . $account->customer->linkTo(25)
                    . '</td>';
            }
            $html .= '<tr>';

            $html = $html
                . '<td><a href="/'
                . $account->network
                . '/accounts">'
                . $account->network
                . '</a></td>'

                . $holderHTML

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
