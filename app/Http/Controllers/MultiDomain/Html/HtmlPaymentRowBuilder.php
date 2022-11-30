<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Html;

/**
 * Builds HTML table rows to display
 * information about a collection of
 * payments.
 */
class HtmlPaymentRowBuilder implements
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
        foreach ($models->sortByDesc('timestamp') as $payment) {
            // Determine originator/beneficiary details
            if ($payment->originator->networkAccountName) {
                $originatorName = $payment->originator->networkAccountName;
            } else {
                $originatorName = $payment->originator->label;
            }

            if ($payment->beneficiary->networkAccountName) {
                $beneficiaryName = $payment->beneficiary->networkAccountName;
            } else {
                $beneficiaryName = $payment->beneficiary->label;
            }

            // Center/pad payment money
            $money = $payment->currency->code
                . ' ' . $payment->formatAmount();
            //$money = str_pad($money, 10 - (int) round(strlen($money) / 2), ' ', STR_PAD_LEFT);
            $money = str_pad($money, 20, ' ', STR_PAD_BOTH);
            $money = str_replace(' ', '&nbsp;', $money);

            $html .= '<tr style="white-space: nowrap;">';

            $html = $html
                . '<td><a href="/'
                . $payment->network
                . '/payments">'
                . $payment->network
                . '</a></td>'

                . '<td>'
                . $payment->timestamp
                . '</td>'

                . '<td>“' . $payment->memo . '”</td>'

                . '<td style="text-align: right;"><a href="/account/'
                . $payment->originator->identifier
                . '">'
                . (new HtmlStringShortener())->shorten($originatorName, 23)
                . '</a></td>'

                . '<td style="text-align: center; color: '
                . $payment->state->getColor()
                . '; font-family: monospace, monospace;">━┫<a href="/payment/'
                . $payment->id
                . '">'
                . $money
                . '</a>'
                . $payment->state->getEmoji()
                . '</td>'

                . '<td><a href="/account/'
                . $payment->beneficiary->identifier
                . '">'
                . (new HtmlStringShortener())->shorten($beneficiaryName, 23)
                . '</a></td>';

            $html .= '</tr>';
        }
        $html .= '</table>';

        return $html;
    }
}
