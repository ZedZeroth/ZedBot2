<?php

declare(strict_types=1);

namespace App\Console\Commands;

class ExceptionInformer
{
    /**
     * Outputs relevent exception information
     * to the log and the CLI.
     *
     * @param Command $command
     * @param Exception|Error $e
     * @return void
     */
    public function warn(
        \Illuminate\Console\Command $command,
        \Exception|\Error $e
    ): void {
        /*💬*/ //print_r($e);

        // Validate the command
        (new CommandValidator())->validate(
            command: $command,
            commandName: $command->argument('command')
        );

        // Validate $e->getMessage()
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: $e->getMessage(),
            stringName: '$e->getMessage()',
            shortestLength: 1,
            longestLength: pow(10, 3),
            mustHaveUppercase: false,
            canHaveUppercase: true,
            mustHaveLowercase: false,
            canHaveLowercase: true,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: false
        );

        // Validate $e->getFile()
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: str_replace(['/', '.'], '', $e->getFile()),
            stringName: $e->getFile(),
            shortestLength: 1,
            longestLength: pow(10, 2),
            mustHaveUppercase: false,
            canHaveUppercase: true,
            mustHaveLowercase: false,
            canHaveLowercase: true,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: true
        );

        // Validate $e->getLine()
        (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
            integer: (int) $e->getLine(),
            integerName: '$e->getLine()',
            lowestValue: 1,
            highestValue: pow(10, 3)
        );

        // Explode exception path
        $exceptionPath = explode('\\', $e::class); // Multi-line

        // Store the details in an array
        $errorDetails = [
            '',
            '[💀] '
            . $exceptionPath[
                array_key_last($exceptionPath)
            ],
            '---------------------------------',
            'Message:   ' . $e->getMessage(),
            'Exception: ' . $e::class,
            'File:      ' . $e->getFile(),
            'Line:      ' . $e->getLine(),
            '---------------------------------',
            ''
        ];

        // Push each detail to CLI/log
        foreach ($errorDetails as $detail) {
            $command->warn($detail);
            \Illuminate\Support\Facades\Log::error($detail);
        }
    }
}
