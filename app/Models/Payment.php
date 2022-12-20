<?php

declare(strict_types=1);

namespace App\Models;

class Payment extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Spatie\ModelStates\HasStates;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'state' => \App\Models\Payments\States\PaymentState::class
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected /* Do not define */ $guarded = [];

    /**
     * Defines the payment's currency.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Defines the originator account.
     */
    public function originator()
    {
        return $this->belongsTo(Account::class, 'originator_id');
    }

    /**
     * Defines the beneficiary account.
     */
    public function beneficiary()
    {
        return $this->belongsTo(Account::class, 'beneficiary_id');
    }

    /**
     * Formats the payment amount into
     * its standard denomination.
     *
     * @return string
     */
    public function formatAmount(): string
    {
        return (new \App\Http\Controllers\MultiDomain\Money\MoneyFormatter())
        ->format(
            amount: $this->amount,
            currency: $this->currency()->first()
        );
    }

    /**
     * Returns an HTML table row for the payment.
     *
     * @return string
     */
    public function tableRow(): string
    {
        // VALIDATION

        // Null timestamps
        $timestampHTML = '<span style="font-style: italic;">UNKNOWN</span>';
        if ($this->timestamp) {
            $timestampHTML = $this->timestamp;
        }

        // Center/pad payment money
        $money = $this->currency->code
            . ' ' . $this->formatAmount();
        $money = str_pad($money, 23, ' ', STR_PAD_BOTH);
        $money = str_replace(' ', '&nbsp;', $money);

        $html = '<tr style="white-space: nowrap;">';

        $html = $html
            . '<td><a href="/'
            . $this->network
            . '/payments">'
            . $this->network
            . '</a></td>'

            . '<td>'
            . $timestampHTML
            . '</td>'

            . '<td>'
            . $this->linkTo('counterparty', 23)
            . '</td>'

            . '<td>“'
            . (new \App\Http\Controllers\MultiDomain\Html\HtmlStringShortener())
                ->shorten($this->memo, 20)
            . '”</td>'

            . '<td style="text-align: right;">'
            . $this->linkTo('originator', 20)
            . '</td>'

            . '<td style="font-family: monospace, monospace;">'
            . '<style>.paymentstateoverride'
            . $this->id
            . ' {color: '
            . $this->state->getColor()
            . ';"}</style>'
            . '💸 <a class="paymentstateoverride'
            . $this->id
            . '" href="/payment/'
            . $this->id
            . '">'
            . $money
            . '</a> '
            . $this->state->getEmoji()
            . '</td>'

            . '<td>'
            . $this->linkTo('beneficiary', 20)
            . '</td>';

        $html .= '</tr>';

        return $html;
    }

    /**
     * Returns an HTML link to either the
     * originator, beneficiary, or counterparty.
     *
     * @param string $target
     * @param int $length
     * @return string
     */
    public function linkTo(
        string $target,
        int $length
    ): string {
        // VALIDATION

        // Originator / beneficiary account link
        if ($target == 'originator' or $target == 'beneficiary') {
            if ($this->$target) {
                if ($this->$target->networkAccountName) {
                    $name = $this->$target->networkAccountName;
                } else {
                    $name = $this->$target->label;
                }
                $name = (new \App\Http\Controllers\MultiDomain\Html\HtmlStringShortener())
                    ->shorten($name, $length);
                $html = '<a href="/account/'
                    . $this->$target->identifier . '">'
                    . $name . '</a>';
                if ($target == 'originator') {
                    return $html . ' 👛';
                } else {
                    return  '👛 ' . $html;
                }
            } else {
                return '<span style="font-style: italic;">COUNTERPARTY</span>';
            }
            // Counterparty customer link
        } elseif ($target == 'counterparty') {
            return $this->linkToCounterparty($length);
        } else {
            throw new \Exception('Invalid payment link target');
        }

        return '';
    }

    /**
     * Returns an HTML link to a counterparty.
     *
     * @param int $length
     * @return string
     */
    public function linkToCounterparty(
        int $length
    ): string {
        $customer = null;
        $emoji = '❓';
        $color = 'grey';
        if ($this->originator) {
            if ($this->originator->customer) {
                // If the originator is SELF
                if (
                    $this->originator->customer->identifier
                        == config('app.ZED_SELF_CUSTOMER_IDENTIFIER')
                ) {
                    $emoji = '📤';
                    $color = 'red';
                // Else the originator is the counterparty
                } elseif ($this->beneficiary) {
                    $customer = $this->originator->customer;
                }
            }
        }
        if ($this->beneficiary) {
            if ($this->beneficiary->customer) {
                // If the beneficiary is SELF
                if (
                    $this->beneficiary->customer->identifier
                        == config('app.ZED_SELF_CUSTOMER_IDENTIFIER')
                ) {
                    $emoji = '📥';
                    $color = 'green';
                // Else the beneficiary is the counterparty
                } elseif ($this->originator) {
                    $customer = $this->beneficiary->customer;
                }
            }
        }
        if ($this->originator and $this->beneficiary) {
            if ($this->beneficiary->customer and $this->originator->customer) {
                // If payment to/from SELF
                if (
                    $this->originator->customer->identifier
                        == config('app.ZED_SELF_CUSTOMER_IDENTIFIER')
                    and
                    $this->beneficiary->customer->identifier
                        == config('app.ZED_SELF_CUSTOMER_IDENTIFIER')
                ) {
                    $emoji = '🔁';
                    $color = 'orange';
                    $customer = $this->beneficiary->customer;
                }
            }
        }
        if ($customer) {
            return $emoji
                . ' <style>.creditdebitoverride'
                . $this->id
                . ' a {color: '
                . $color
                . ';"}</style><span class="creditdebitoverride'
                . $this->id
                . '">'
                . $customer->linkTo($length)
                . '</span>';
        } else {
            if ($this->currency->code == 'GBP') {
                return $emoji
                    . ' <span style="font-weight: bold; color: '
                    . $color
                    . ';">⚠️ UNKNOWN ⚠️</a>';
            } else {
                return $emoji
                    . ' <span style="font-style: italic; color: '
                    . $color
                    . ';">UNKNOWN</a>';
            }
        }
    }
}
