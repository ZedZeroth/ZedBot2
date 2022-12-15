<?php

declare(strict_types=1);

namespace App\Models;

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
    * Get the credits for this customer.
    */
    public function credits()
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
    public function debits()
    {
        $debits = collect();
        foreach ($this->accounts()->get() as $account) {
            $debits = $debits->merge($account->debits()->get());
        }
        return $debits;
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
        return '🏥 ' . $this->flag($this->placeOfBirth) . ' • '
            . '🌍 ' . $this->flag($this->nationality) . ' • '
            . '🏠 ' . $this->flag($this->residency);
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
}
