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
use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Server\Handler\Contract\InputHandlerContract;
use Valkyrja\Cli\Server\Handler\InputHandler;
use Valkyrja\Cli\Server\Middleware\LogThrowableCaughtMiddleware;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Log\Logger\Contract\LoggerContract;

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
    public static function publishInputHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            InputHandlerContract::class,
            new InputHandler(
                container: $container,
                router: $container->getSingleton(RouterContract::class),
                inputReceivedHandler: $container->getSingleton(InputReceivedHandlerContract::class),
                throwableCaughtHandler: $container->getSingleton(ThrowableCaughtHandlerContract::class),
                exitedHandler: $container->getSingleton(ExitedHandlerContract::class),
                interactionConfig: $config
            ),
        );
    }

    /**
     * Publish the LogThrowableCaughtMiddleware service.
     */
    public static function publishLogThrowableCaughtMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            LogThrowableCaughtMiddleware::class,
            new LogThrowableCaughtMiddleware(
                logger: $container->getSingleton(LoggerContract::class),
            )
        );
    }
}
