<?php

declare(strict_types=1);

namespace App\Http\Controllers\Html;

use Illuminate\Support\Collection;

/**
 * Builds HTML table rows to display
 * information about a collection of
 * accounts.
 */
class HtmlAccountRowBuilder implements
    HtmlCollectionRowBuilderInterface
{
    /**
     * @param Collection $models
     * @return string
     */
    public function build(
        Collection $models
    ): string {
        $html = '<table>';
        foreach ($models as $account) {
            if ($account->networkAccountName) {
                $accountName = $account->networkAccountName;
            } else {
                $accountName = $account->label;
            }

            $html .= '<tr>';

            $html = $html
                . '<td><a href="/'
                . $account->network
                . '/accounts">'
                . $account->network
                . '</a></td>'

                . '<td style="font-weight: bold;">'
                . '{HOLDER}'
                . '</td>'

                . '<td>“' . $accountName . '”</td>'

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
