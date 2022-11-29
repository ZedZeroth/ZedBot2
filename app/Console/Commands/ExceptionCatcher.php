<?php

declare(strict_types=1);

namespace App\Console\Commands;

class ExceptionCatcher
{
    /**
     * Catches exeptions that have been passed
     * up to the initial command.
     *
     * @param Command $command
     * @return void
     */
    public function catch(
        \Illuminate\Console\Command $command
    ): void {

        // Validate the command
        (new CommandValidator())->validate(
            command: $command,
            commandName: $command->argument('command')
        );

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
        // Custom validation exceptions
        } catch (\App\Http\Controllers\MultiDomain\Validators\StringValidationException $e) {
            $exceptionCaught = $e;
        } catch (\App\Http\Controllers\MultiDomain\Validators\IntegerValidationException $e) {
            $exceptionCaught = $e;
        } catch (\App\Http\Controllers\MultiDomain\Validators\ArrayValidationException $e) {
            $exceptionCaught = $e;
        } catch (\App\Http\Controllers\MultiDomain\Validators\TimestampValidationException $e) {
            $exceptionCaught = $e;
        } catch (CommandValidationException $e) {
            $exceptionCaught = $e;
        } catch (\App\Http\Controllers\MultiDomain\Validators\AdapterValidationException $e) {
            $exceptionCaught = $e;
        } catch (\App\Http\Controllers\MultiDomain\Validators\ApiValidationException $e) {
            $exceptionCaught = $e;
        } catch (\App\Http\Controllers\MultiDomain\Validators\DtoValidationException $e) {
            $exceptionCaught = $e;
        }

        if ($exceptionCaught) {
            (new ExceptionInformer())->warn(
                command: $command,
                e: $exceptionCaught
            );
        }
    }
}
