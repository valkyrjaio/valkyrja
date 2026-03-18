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

use Override;
use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Entry\Abstract\App;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Input\Factory\InputFactory;
use Valkyrja\Cli\Server\Handler\Contract\InputHandlerContract;

class Cli extends App
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function run(Env $env, Config|CliConfig $config): void
    {
        if (! $config instanceof CliConfig) {
            throw new InvalidArgumentException('Config must be an instance of CliConfig');
        }

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
    protected static function getInput(CliConfig $config): InputContract
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
