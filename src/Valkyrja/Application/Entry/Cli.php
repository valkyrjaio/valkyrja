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
use Valkyrja\Application\Entry\Abstract\App;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Factory\InputFactory;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Server\Handler\Contract\InputHandlerContract;

class Cli extends App
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function run(string $dir, Env $env): void
    {
        $app = static::start(
            dir: $dir,
            env: $env,
        );

        $container = $app->getContainer();

        self::bootstrapThrowableHandler($app, $container);

        $handler = $container->getSingleton(InputHandlerContract::class);
        $input   = static::getInput(env: $env);
        $handler->run($input);
    }

    /**
     * Get the input.
     */
    protected static function getInput(Env $env): InputContract
    {
        /** @var non-empty-string $commandName */
        $commandName = $env::APP_CLI_DEFAULT_COMMAND_NAME;

        $input = InputFactory::fromGlobals(
            commandName: $commandName
        );

        return $input;
    }
}
