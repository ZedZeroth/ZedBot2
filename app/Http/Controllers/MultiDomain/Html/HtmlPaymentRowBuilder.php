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
            // Null timestamps
            $timestampHTML = '<span style="font-style: italic;">UNKNOWN</span>';
            if ($payment->timestamp) {
                $timestampHTML = $payment->timestamp;
            }

            $customerName = '<span style="font-style: italic;">UNKNOWN</span>';
            if ($payment->currency->code == 'GBP') {
                $customerName = '<span style="font-weight: bold;">⚠️ UNKNOWN ⚠️</span>';
            }
            $customer = null;
            $directionEmoji = '❓';
            $directionColor = 'grey';
            // Null originator/beneficiary (and their networkAccountName)
            $originatorLink = '<span style="font-style: italic;">OTHER/MULTI</span>';
            if ($payment->originator) {
                if ($payment->originator->networkAccountName) {
                    $originatorName = $payment->originator->networkAccountName;
                } else {
                    $originatorName = $payment->originator->label;
                }
                $originatorName = (new HtmlStringShortener())->shorten($originatorName, 23);
                $originatorLink = '<a href="/account/'
                    . $payment->originator->identifier . '">'
                    . $originatorName . '</a>';
                if ($payment->originator->customer) {
                    if (
                        $payment->originator->customer->identifier
                        != config('app.ZED_SELF_CUSTOMER_IDENTIFIER')
                    ) {
                        $customer = $payment->originator->customer;
                        $directionColor = 'green';
                    } else {
                        $directionEmoji = '📤';
                    }
                }
            }
            $beneficiaryLink = '<span style="font-style: italic;">OTHER/MULTI</span>';
            if ($payment->beneficiary) {
                if ($payment->beneficiary->networkAccountName) {
                    $beneficiaryName = $payment->beneficiary->networkAccountName;
                } else {
                    $beneficiaryName = $payment->beneficiary->label;
                }
                $beneficiaryName = (new HtmlStringShortener())->shorten($beneficiaryName, 23);
                $beneficiaryLink = '<a href="/account/'
                    . $payment->beneficiary->identifier . '">'
                    . $beneficiaryName . '</a>';
                if ($payment->beneficiary->customer) {
                    if (
                        $payment->beneficiary->customer->identifier
                        != config('app.ZED_SELF_CUSTOMER_IDENTIFIER')
                    ) {
                        $customer = $payment->beneficiary->customer;
                        $directionColor = 'red';
                    } else {
                        $directionEmoji = '📥';
                    }
                }
            }
            $customerHTML = $directionEmoji . ' ' . $customerName;
            if ($customer) {
                $customerName = (
                    new HtmlStringShortener())->shorten(
                        $customer->familyName . ', ' . $customer->givenName1,
                        23
                    );
                $customerHTML = $directionEmoji
                    . ' <a style="color: '
                    . $directionColor
                    . ';" href="/customer/'
                    . $customer->identifier
                    . '">'
                    . $customerName
                    . '</a>';
            }

            // Null account holder
            $timestampHTML = '<span style="font-style: italic;">UNKNOWN</span>';
            if ($payment->timestamp) {
                $timestampHTML = $payment->timestamp;
            }

            // Center/pad payment money
            $money = $payment->currency->code
                . ' ' . $payment->formatAmount();
            //$money = str_pad($money, 10 - (int) round(strlen($money) / 2), ' ', STR_PAD_LEFT);
            $money = str_pad($money, 23, ' ', STR_PAD_BOTH);
            $money = str_replace(' ', '&nbsp;', $money);

            $html .= '<tr style="white-space: nowrap;">';

            $html = $html
                . '<td><a href="/'
                . $payment->network
                . '/payments">'
                . $payment->network
                . '</a></td>'

                . '<td>'
                . $timestampHTML
                . '</td>'

                . '<td>'
                . $customerHTML
                . '</td>'

                . '<td>“' . $payment->memo . '”</td>'

                . '<td style="text-align: right;">'
                . $originatorLink
                . '</td>'

                . '<td style="text-align: center; color: '
                . $payment->state->getColor()
                . '; font-family: monospace, monospace;">━┫<a href="/payment/'
                . $payment->id
                . '">'
                . $money
                . '</a>'
                . $payment->state->getEmoji()
                . '</td>'

                . '<td>'
                . $beneficiaryLink
                . '</td>';

            $html .= '</tr>';
        }
        $html .= '</table>';

        return $html;
    }
}
