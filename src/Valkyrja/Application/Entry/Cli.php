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

namespace Valkyrja\Application\Entry;

use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Application\Entry\Abstract\App;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Input\Factory\InputFactory;
use Valkyrja\Cli\Server\Handler\Contract\InputHandlerContract;

class Cli extends App
{
    /**
     * Run the cli app.
     */
    public static function run(CliConfigContract $config, Env $env = new Env()): void
    {
        $app = static::start(
            env: $env,
            config: $config,
        );

        $container = $app->getContainer();

        self::bootstrapThrowableHandler($app, $container);

        $handler = $container->getSingleton(InputHandlerContract::class);
        $input   = static::getInput(config: $config);
        $handler->run($input);
    }

    /**
     * Get the input.
     */
    public static function getInput(CliConfigContract $config): InputContract
    {
        /** @var non-empty-string[] $args */
        $args = $_SERVER['argv'] ?? [];

        $input = InputFactory::fromGlobals(
            args: $args,
            applicationName: $config->applicationName,
            commandName: $config->defaultCommandName,
        );

        return $input;
    }
}
