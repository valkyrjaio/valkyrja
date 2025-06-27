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

namespace Valkyrja\Cli\Server\Provider;

use Valkyrja\Application\Config\Valkyrja;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Contract\Router;
use Valkyrja\Cli\Server\Contract\InputHandler;
use Valkyrja\Cli\Server\Middleware\LogThrowableCaughtMiddleware;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Contract\Logger;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            InputHandler::class                 => [self::class, 'publishInputHandler'],
            LogThrowableCaughtMiddleware::class => [self::class, 'publishLogThrowableCaughtMiddleware'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            InputHandler::class,
            LogThrowableCaughtMiddleware::class,
        ];
    }

    /**
     * Publish the input handler service.
     */
    public static function publishInputHandler(Container $container): void
    {
        $config = $container->getSingleton(Valkyrja::class);

        $container->setSingleton(
            InputHandler::class,
            new \Valkyrja\Cli\Server\InputHandler(
                container: $container,
                router: $container->getSingleton(Router::class),
                inputReceivedHandler: $container->getSingleton(InputReceivedHandler::class),
                throwableCaughtHandler: $container->getSingleton(ThrowableCaughtHandler::class),
                exitedHandler: $container->getSingleton(ExitedHandler::class),
                interactionConfig: $config->cli->interaction
            ),
        );
    }

    /**
     * Publish the LogThrowableCaughtMiddleware service.
     */
    public static function publishLogThrowableCaughtMiddleware(Container $container): void
    {
        $container->setSingleton(
            LogThrowableCaughtMiddleware::class,
            new LogThrowableCaughtMiddleware(
                logger: $container->getSingleton(Logger::class),
            )
        );
    }
}
