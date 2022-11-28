<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\MultiDomain\Validators\StringValidator;
use App\Http\Controllers\MultiDomain\Validators\IntegerValidator;
use App\Http\Controllers\MultiDomain\Validators\APIValidator;

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

        // Validate the command arguments
        (new StringValidator())->validate(
            string: $command->argument('API'),
            stringName: 'API',
            shortestLength: 4,
            longestLength: 4,
            containsUppercase: true,
            containsLowercase: false,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: true
        );

        // Validate API code exists
        (new APIValidator())->validate(apiCode: $command->argument('API'));

        // Validate number to fetch
        (new IntegerValidator())->validate(
            integer: (int) $command->argument('Number to fetch'),
            integerName: 'Number to fetch',
            lowestValue: 1,
            highestValue: pow(10, 6)
        );

        // Validate command source
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
