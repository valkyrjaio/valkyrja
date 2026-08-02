<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Input\Factory;

use Valkyrja\Cli\Interaction\Argument\Factory\ArgumentFactory;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Option\Factory\OptionFactory;

use function str_starts_with;

abstract class InputFactory
{
    /**
     * Create an input from given global variables.
     *
     * @param non-empty-string[] $args            The arguments
     * @param non-empty-string   $applicationName The default application name (this will be overridden by the actual entry point)
     * @param non-empty-string   $commandName     The default command name to use in case one was not passed in
     */
    public static function fromGlobals(array $args, string $applicationName, string $commandName): InputContract
    {
        $input = new Input();

        return static::inputWithProperties($input, $args, $applicationName, $commandName);
    }

    /**
     * Create a new instance of a given input with all properties set.
     *
     * @param non-empty-string[] $args            The arguments
     * @param non-empty-string   $applicationName The default application name (this will be overridden by the actual entry point)
     * @param non-empty-string   $commandName     The default command name to use in case one was not passed in
     */
    protected static function inputWithProperties(InputContract $input, array $args, string $applicationName, string $commandName): InputContract
    {
        $arguments    = [];
        $options      = [];
        $endOfOptions = false;

        /** @var non-empty-string $arg */
        foreach ($args as $key => $arg) {
            if ($key === 0) {
                $applicationName = $arg;
            } elseif (! $endOfOptions && $arg === '--') {
                // POSIX end-of-options marker: the `--` itself is consumed, and every arg after
                // it is an operand — never an option, however many dashes it starts with. A
                // second `--` is therefore an ordinary operand.
                $endOfOptions = true;
            } elseif (! $endOfOptions && $arg !== '-' && str_starts_with($arg, '-')) {
                // A lone `-` is an operand by convention (it names standard input), not an option.
                $options = [
                    ...$options,
                    ...OptionFactory::fromArg($arg),
                ];
            } elseif ($key === 1) {
                // The first key that also is not an option starting with -, or --
                $commandName = $arg;
            } else {
                $arguments[] = ArgumentFactory::fromArg($arg);
            }
        }

        return $input
            ->withCaller($applicationName)
            ->withCommandName($commandName)
            ->withArguments(...$arguments)
            ->withOptions(...$options);
    }
}
