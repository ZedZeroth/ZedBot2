<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\MultiDomain\Validators\StringValidator;
use App\Http\Controllers\MultiDomain\Validators\IntegerValidator;

class CommandValidator
{
    /**
     * Checks a command based on various conditions.
     *
     * @param Command $command
     * @param string $commandName
     * @return bool
     */
    public function validate(
        Command $command,
        string $commandName
    ): bool {
        $prefix = '"' . $commandName . '" command\'s';

        (new StringValidator())->validate(
            string: $command->argument('API'),
            stringName: 'API',
            shortestLength: 3,
            longestLength: 4,
            containsUppercase: true,
            containsLowercase: false,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: true
        );

        (new IntegerValidator())->validate(
            integer: $command->argument('Number to fetch'),
            integerName: 'Number to fetch',
            lowestValue: 1,
            highestValue: pow(10, 6)
        );

        if (
            !$this->getEmojiFromCommandSource(
                $command->argument('source')
            )
        ) {
            throw new CommandValidationException(
                message: $prefix . 'source of "' . $command->argument('source') . '" is invalid'
            );
        } else {
            return true;
        }
    }

    /**
     * Matches a command's source input interface
     * to a more concise emoji
     *
     * @param string $source
     * @return ?string
     */
    public function getEmojiFromCommandSource(string $source): ?string
    {
        return match ($source) {
            'cli'       => '📟',
            'browser'   => '🖱️ ',
            'scheduler' => '🕑',
            'auto'      => '🤖',
            default     => null
        };
    }
}
