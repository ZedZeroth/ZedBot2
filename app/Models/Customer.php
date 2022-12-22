<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Collection;

class Customer extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Spatie\ModelStates\HasStates;

    /**
     * The default attributes.
     *
     * @var array<int, string>
     */
    protected /* Do not define */ $attributes = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'state' => \App\Models\Customers\States\CustomerState::class
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected /* Do not define */ $guarded = [];

    /**
    * Get the accounts for this customer.
    */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
    * Get the contacts for this customer.
    */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
    * Get the identity documents for this customer.
    */
    public function identityDocuments()
    {
        return $this->hasMany(IdentityDocument::class);
    }

    /**
    * Get the risk assessments for this customer.
    */
    public function riskAssessments()
    {
        return $this->hasMany(RiskAssessment::class);
    }

    /**
    * Get the credits for this customer.
    */
    public function credits(): Collection
    {
        $credits = collect();
        foreach ($this->accounts()->get() as $account) {
            $credits = $credits->merge($account->credits()->get());
        }
        return $credits;
    }

    /**
    * Get the debits for this customer.
    */
    public function debits(): Collection
    {
        $debits = collect();
        foreach ($this->accounts()->get() as $account) {
            $debits = $debits->merge($account->debits()->get());
        }
        return $debits;
    }

    /**
     * Returns a customer's payments.
     *
     * @return Collection
     */
    public function payments(): Collection
    {
        return $this->credits()->merge($this->debits());
    }

    /**
     * Returns their full name
     *
     * @return string
     */
    public function fullName(): string
    {
        $fullName =
            $this->familyName . ', '
            . $this->givenName1;

        if ($this->givenName2) {
            $fullName .= ' ' . $this->givenName2;
        }

        return $fullName;
    }

    /**
     * Returns an HTML link to a customer.
     *
     * @param int $length
     * @return string
     */
    public function linkTo(
        int $length
    ): string {
        // VALIDATION

        $name = (
            new \App\Http\Controllers\MultiDomain\Html\HtmlStringShortener())
            ->shorten(
                $this->familyName . ', ' . $this->givenName1,
                $length
            );
        return '🗿 <a href="/customer/'
            . $this->identifier
            . '">'
            . $name
            . '</a>';
    }

    /**
     * Returns their age
     *
     * @return string
     */
    public function age(): string
    {
        if ($this->dateOfBirth) {
            return (string) (new \DateTime($this->dateOfBirth))
            ->diff(now())
            ->y;
        } else {
            return '?';
        }
    }

    /**
     * Returns country flag emoji
     *
     * @param ?string $code
     * @return string
     */
    public function flag(
        ?string $code
    ): string {
        if (!$code) {
            return '❓';
        }
        $emoji = [];
        foreach (str_split($code) as $c) {
            if (($o = ord($c)) > 64 && $o % 32 < 27) {
                $emoji[] = hex2bin("f09f87" . dechex($o % 32 + 165));
                continue;
            }
            $emoji[] = $c;
        }
        return '<span style="cursor: default;" title="'
            . \Locale::getDisplayRegion('-' . $code, 'en')
            . '">'
            . join($emoji)
            . '</span>';
    }

    /**
     * Returns a customer's location data
     *
     * @return string
     */
    public function location(): string
    {
        return '<table style="border-collapse: collapse;"><tr>'
            . '<td style="padding: 0; margin: 0;">🏥 🌍 🏠</td></tr>'
            . '<tr><td style="padding: 0; margin: 0;">'
            . $this->flag($this->placeOfBirth) . ' '
            . $this->flag($this->nationality) . ' '
            . $this->flag($this->residency) . ' '
            . '</td></tr></table>';
    }

    /**
     * Represents the customer's volume snapshot
     *
     * @return string
     */
    public function volumeEmojis(): string
    {
        if (!$this->volumeSnapshot) {
            return '❓';
        }
        $emojis = '';
        for ($i = 0; $i < (int) log10($this->volumeSnapshot / 100); $i++) {
            $emojis .= '🤑';
        }
        return '<span style="cursor: default;" title="'
            . number_format($this->volumeSnapshot, 0, '.', ',')
            . '">'
            . $emojis
            . '</span>';
    }

    /**
     * Represents the customer's age
     *
     * @return string
     */
    public function ageEmojis(): string
    {
        if (!$this->dateOfBirth) {
            return '❓';
        }
        if ($this->age() > 60) {
            $emoji = '🧓🏽';
        } elseif ($this->age() > 30) {
            $emoji = '🧑🏽';
        } else {
            $emoji = '👶🏽';
        }
        return '<span style="cursor: default;" title="'
            . $this->age()
            . '">'
            . $emoji
            . '</span>';
    }

    /**
     * Creates/updates a new risk assessment for the customer
     *
     * @param ?string $type
     * @return RiskAssessment
     */
    public function assess(
        ?string $type = null
    ): bool {
        if ($type) {
            if (in_array($type, config('app.ZED_RISK_ASSESSMENT_TYPES'))) {
                $typeArray = [$type];
            } else {
                throw new \Exception('RiskAssessment type "' . $type . '" does not exist');
            }
        } else {
            $typeArray = config('app.ZED_RISK_ASSESSMENT_TYPES');
        }

        foreach ($typeArray as $assessmentType) {
            $assessorName = '\App\Http\Controllers\Customers\Assess\Customer'
                . $assessmentType
                . 'RiskAssessor';
            (new $assessorName())->assess($this);
        }

        return true;
    }

    /**
     * Represents the customer's risk assessments
     *
     * @return string
     */
    public function riskAssessmentEmojis(): string
    {
        // Generate string
        $string = '<table style="border-collapse: collapse;"><tr>';
        foreach ($this->riskAssessments as $riskAssessment) {
            $string .= '<td style="padding: 0; margin: 0;">&nbsp;'
                . $riskAssessment->tag()
                . '&nbsp;</td>';
        }
        $string .= '</tr><tr>';
        foreach ($this->riskAssessments as $riskAssessment) {
            $string .= '<td style="padding: 0; margin: 0;">&nbsp;'
                . $riskAssessment->emoji()
                . '&nbsp;</td>';
        }
        $string .= '</tr></table>';
        return $string;
    }

    /**
     * Returns a customer's volume by currency
     * over a given number of days.
     *
     * @param string $currency
     * @param int $days
     * @param bool $formatted
     * @return int|string
     */
    public function volume(
        string $currency,
        int $days,
        ?bool $formatted = false
    ): int|string {
        $volume = 0;
        foreach ($this->payments() as $payment) {
            if (
                $payment->currency->code == $currency
                and
                (int) (new \DateTime(
                    $payment->timestamp
                ))->diff(now())->format('%a') < $days
            ) {
                $volume += $payment->amount;
            }
        }
        if ($formatted) {
            return (string) (new \App\Http\Controllers\MultiDomain\Money\MoneyFormatter())
                ->format(
                    amount: $volume,
                    currency: \App\Models\Currency::where('code', $currency)->firstOrFail()
                );
        } else {
            return (int) $volume;
        }
    }

    /**
     * Returns a customer's velocity by currency
     * over a given number of days.
     *
     * @param string $currency
     * @param int $days
     * @return int
     */
    public function velocity(
        string $currency,
        int $days
    ): int {
        $velocity = 0;
        foreach ($this->payments() as $payment) {
            if (
                $payment->currency->code == $currency
                and
                (int) (new \DateTime(
                    $payment->timestamp
                ))->diff(now())->format('%a') < $days
            ) {
                $velocity++;
            }
        }
        return (int) $velocity;
    }
}
