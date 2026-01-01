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

namespace Valkyrja\Cli\Interaction\Factory;

use Valkyrja\Cli\Interaction\Input\Input;

/**
 * Class InputFactory.
 */
abstract class InputFactory
{
    /**
     * @param non-empty-string[]|null $args  The arguments
     * @param class-string<Input>     $class The Input class to return
     *
     * @return Input
     */
    public static function fromGlobals(
        array|null $args = null,
        string $class = Input::class,
    ): Input {
        $args ??= $_SERVER['argv'] ?? [];

        /** @var non-empty-string[] $args */
        $applicationName = 'valkyrja';
        $commandName     = 'list';
        $arguments       = [];
        $options         = [];

        $input = new $class();

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
