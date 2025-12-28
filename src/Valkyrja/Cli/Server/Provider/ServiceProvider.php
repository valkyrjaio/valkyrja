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

use Override;
use Valkyrja\Cli\Interaction\Config;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Dispatcher\Contract\Router;
use Valkyrja\Cli\Server\Handler\Contract\InputHandler as InputHandlerContract;
use Valkyrja\Cli\Server\Handler\InputHandler;
use Valkyrja\Cli\Server\Middleware\LogThrowableCaughtMiddleware;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Logger\Contract\Logger;

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
    #[Override]
    public static function publishers(): array
    {
        return [
            InputHandlerContract::class         => [self::class, 'publishInputHandler'],
            LogThrowableCaughtMiddleware::class => [self::class, 'publishLogThrowableCaughtMiddleware'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            InputHandlerContract::class,
            LogThrowableCaughtMiddleware::class,
        ];
    }

    /**
     * Publish the input handler service.
     */
    public static function publishInputHandler(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            InputHandlerContract::class,
            new InputHandler(
                container: $container,
                router: $container->getSingleton(Router::class),
                inputReceivedHandler: $container->getSingleton(InputReceivedHandler::class),
                throwableCaughtHandler: $container->getSingleton(ThrowableCaughtHandler::class),
                exitedHandler: $container->getSingleton(ExitedHandler::class),
                interactionConfig: $config
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
