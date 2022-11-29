<?php

declare(strict_types=1);

namespace App\Console\Commands;

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
        \Illuminate\Console\Command $command,
        string $commandName
    ): bool {
        $prefix = '"' . $commandName . '" command\'s';

        // Validate command name
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: str_replace(':', '', $commandName),
            stringName: 'commandName',
            charactersToRemove: [],
            shortestLength: 12,
            longestLength: 18,
            mustHaveUppercase: false,
            canHaveUppercase: false,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: false
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

        // Validate API code is a valid string and exists in the list
        if ($command->argument('API')) {
            (new \App\Http\Controllers\MultiDomain\Validators\ApiValidator())
                ->validate(apiCode: $command->argument('API'));
        }

        // Validate number to fetch
        if ($command->argument('Number to fetch')) {
            (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
                integer: (int) $command->argument('Number to fetch'),
                integerName: 'Number to fetch',
                lowestValue: 1,
                highestValue: pow(10, 6) // Maximum for payments
            );
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
        // Validate source name
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: $source,
            stringName: 'source',
            charactersToRemove: [],
            shortestLength: 3,
            longestLength: 9,
            mustHaveUppercase: false,
            canHaveUppercase: false,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: false
        );

        return match ($source) {
            'cli'       => '📟',
            'browser'   => '🖱️ ',
            'scheduler' => '🕑',
            'auto'      => '🤖',
            default     => null
        };
    }
}
