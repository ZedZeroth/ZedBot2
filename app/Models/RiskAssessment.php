<?php

declare(strict_types=1);

namespace App\Models;

class RiskAssessment extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected /* Do not define */ $guarded = [];

    /**
    * Get the customer that this risk assessment is assigned to.
    */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Returns a color span for the risk classification.
     * @param string $string
     * @return string
     */
    public function colorSpan(
        string $string
    ): string {
        return '<span style="color: '
            . match ($this->state) {
                'Lower'             => '#0B0',
                'Standard'          => '#F90',
                'HigherMitigated'   => '#F20',
                'NoData'            => '#333',
                'HigherUnmitigated' => '#000',
            }
            . ';">'
            . $string
            . '</span>';
    }

    /**
     * Returns an emoji for the risk classification.
     *
     * @return string
     */
    public function emoji(): string
    {
        return $this->colorSpan(
            '<span title="'
            . $this->state
            . '"style="font-size: 150%;">'
            . match ($this->state) {
                'Lower'             => '❶',
                'Standard'          => '❷',
                'HigherMitigated'   => '❸',
                'NoData'            => '❹',
                'HigherUnmitigated' => '❺',
            }
            . '</span>'
        );
    }

    /**
     * Returns a short string for the risk assessment type.
     *
     * @return string
     */
    public function tag(): string
    {
        return match ($this->type) {
                'Volume'                => 'VOL',
                'Velocity'              => 'VEL',
        };
    }

    /**
     * Returns an integer value for the risk classification.
     *
     * @return int
     */
    public function score(): int
    {
        return match ($this->state) {
            'Lower'             => 1,
            'Standard'          => 2,
            'HigherMitigated'   => 3,
            'NoData'            => 4,
            'HigherUnmitigated' => 5,
        };
    }
}
