<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExceptionCatcher
{
    /**
     * Catches exeptions that have been passed
     * up to the initial command.
     *
     * @param Command $command
     * @param string $class
     * @param string $function
     * @param int $line
     * @return void
     */
    public function catch(
        Command $command,
        string $class,
        string $function,
        int $line
    ): void {
        $exceptionCaught = null;
        try {
            (new CommandInformer())
                ->run(command: $command);
        // Http exceptions
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $exceptionCaught = $e;
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $exceptionCaught = $e;
        // Not found exceptions
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $exceptionCaught = $e;
        } catch (\Illuminate\Support\ItemNotFoundException $e) {
            $exceptionCaught = $e;
        // Argument errors
        } catch (\TypeError $e) {
            $exceptionCaught = $e;
        } catch (\ArgumentCountError $e) {
            $exceptionCaught = $e;
        } catch (\Error $e) {
            $exceptionCaught = $e;
        // String validation exceptions
        } catch (\App\Http\Controllers\MultiDomain\Validators\StringValidationException $e) {
            $exceptionCaught = $e;
        }

        if ($exceptionCaught) {
            (new ExceptionInformer())->warn(
                command: $command,
                e: $exceptionCaught,
                class: $class,
                function: $function,
                line: $line
            );
        }
    }
}
