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
     * Returns information from their identity documents
     *
     * @param string $info
     * @return string
     */
    public function identityInfo(
        string $info
    ): string {
        foreach ($this->identityDocuments as $identityDocument) {
            if ($identityDocument->$info) {
                return $identityDocument->$info;
            }
        }

        return '';
    }

    /**
     * Returns their date of birth
     *
     * @return string
     */
    public function dateOfBirth(): string
    {
        return $this->identityInfo(info: 'dateOfBirth');
    }

    /**
     * Returns their nationality
     *
     * @return string
     */
    public function nationality(): string
    {
        return $this->identityInfo(info: 'nationality');
    }

    /**
     * Returns their country of birth
     *
     * @return string
     */
    public function placeOfBirth(): string
    {
        return $this->identityInfo(info: 'placeOfBirth');
    }

    /**
     * Returns their residency
     *
     * @return string
     */
    public function residency(): string
    {
        return '';
    }

    /**
     * Returns their age
     *
     * @return int
     */
    public function age(): int
    {
        return (new \DateTime($this->dateOfBirth()))
            ->diff(now())
            ->y;
    }

    /**
     * Returns country flag emoji
     *
     * @param string $code
     * @return string
     */
    public function flag(
        string $code
    ): string {
        if (!$code) {
            return '🏴‍☠️';
        }
        $emoji = [];
        foreach (str_split($code) as $c) {
            if (($o = ord($c)) > 64 && $o % 32 < 27) {
                $emoji[] = hex2bin("f09f87" . dechex($o % 32 + 165));
                continue;
            }
            $emoji[] = $c;
        }
        return join($emoji);
    }

    /**
     * Returns a customer's location data
     *
     * @return string
     */
    public function location(): string
    {
        return '🏠 ' . $this->flag($this->residency()) . ' • '
            . '👶🏽 ' . $this->flag($this->placeOfBirth()) . ' • '
            . '🏝️ ' . $this->flag($this->nationality());
    }
}
