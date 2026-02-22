<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Cli\Interaction\Input\Factory;

use Valkyrja\Cli\Interaction\Argument\Factory\ArgumentFactory;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Option\Factory\OptionFactory;

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
        $arguments = [];
        $options   = [];

        /** @var non-empty-string $arg */
        foreach ($args as $key => $arg) {
            if ($key === 0) {
                $applicationName = $arg;
            } elseif (str_starts_with($arg, '-')) {
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
