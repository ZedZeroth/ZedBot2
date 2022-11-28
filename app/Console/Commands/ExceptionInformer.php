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
     * @param string $class
     * @param string $function
     * @param int $line
     * @return void
     */
    public function warn(
        \Illuminate\Console\Command $command,
        \Exception|\Error $e,
        string $class,
        string $function,
        int $line
    ): void {
        /*💬*/ //print_r($e);

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
