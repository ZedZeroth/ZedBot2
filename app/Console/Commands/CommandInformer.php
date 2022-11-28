<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Payment;

/**
 * When running a command this pushes
 * useful information to the CLI and
 * to the log file.
 */
class CommandInformer
{
    /**
     * The injected command.
     *
     * @var Command $command
     */
    private Command $command;

    /**
     * Outputs useful command information
     * before and after running a command.
     *
     * @param Command $command
     * @return void
     */
    public function run(
        Command $command
    ): void {

        // Validate the command
        (new CommandValidator())->validate(
            command: $command,
            commandName: $command->argument('command')
        );

        // Assign command property
        $this->command = $command;

        // Build and output the title
        $sourceEmoji = (new CommandValidator())->getEmojiFromCommandSource(
            source: $this->command->argument('source')
        );

        $this->output(
            '[' . $sourceEmoji . '] '
            . $this->command->argument('command')
        );
        $this->output('---------------------------------');

        //Output other arguments
        foreach (
            $this->command->argument() as $argumentKey => $argument
        ) {
            if (
                $argumentKey != 'command'
                and $argumentKey != 'source'
            ) {
                $this->output(
                    $argumentKey
                    . ': '
                    . $argument
                );
            }
        }

        //Count models and record current time
        $models = [
            'Account' => Account::all()->count(),
            'Currency' => Currency::all()->count(),
            'Payment' => Payment::all()->count(),
        ];
        $startTime = now();

        //Output 'Running...' and run the command
        $this->output(
            '... Running "'
            . $this->command->argument('command')
            . '"'
        );
        $this->command->runThisCommand();

        // Determine latency
        $latency = now()->diffInMilliseconds($startTime);
        $this->output(
            '... '
            . number_format($latency, 0, '.', ',')
            . 'ms DONE'
        );

        //Determine the number of new models created
        $nothingToUpdate = true;
        foreach ($models as $name => $number) {
            $modelWithPath = '\App\Models\\' . $name;
            $new = $modelWithPath::all()->count() - $number;
            if ($new) {
                $nothingToUpdate = false;
                $this->output(
                    $name
                    . '(s) created: '
                    . $new
                );
            }
        }
        if ($nothingToUpdate) {
            $this->output('No new models created.');
        }

        // Final output
        $this->output('---------------------------------');
        $this->output('');

        return;
    }

    /**
     * Outputs a string to the command line
     * and to the log file.
     *
     * @param string $string
     */
    public function output(
        string $string
    ): void {
        $this->command->info($string);
        Log::info($string);
    }
}
