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

namespace Valkyrja\Container\Provider;

use Valkyrja\Application\Config\Valkyrja;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Env;
use Valkyrja\Application\Support\Provider;
use Valkyrja\Container\CacheableContainer;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Exception\Contract\ErrorHandler as ErrorHandlerContract;

/**
 * Class AppProvider.
 *
 * @author Melech Mizrachi
 */
final class AppProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publish(Application $app): void
    {
        $dataConfig = $app->getConfig();

        $container = new CacheableContainer($dataConfig->container, $app->getDebugMode());

        $app->setContainer($container);

        self::bootstrapServices($app, $container);

        $container->setup();

        // Bootstrap debug capabilities
        self::bootstrapErrorHandler($app, $container);
        // Bootstrap the timezone
        self::bootstrapTimezone($dataConfig);
    }

    /**
     * Bootstrap services.
     */
    protected static function bootstrapServices(Application $app, Container $container): void
    {
        $container->setSingleton(Application::class, $app);
        $container->setSingleton(Env::class, $app->getEnv());
        $container->bindAlias('env', Env::class);
        $container->setSingleton(Valkyrja::class, $app->getConfig());
        $container->setSingleton(Container::class, $container);
    }

    /**
     * Bootstrap error handler.
     */
    protected static function bootstrapErrorHandler(Application $app, Container $container): void
    {
        $config       = $app->getConfig();
        $errorHandler = $config->app->errorHandler;

        // Set error handler in the service container
        $container->setSingleton(ErrorHandlerContract::class, $errorHandler);

        // If debug is on, enable debug handling
        if ($app->getDebugMode()) {
            // Enable error handling
            $errorHandler::enable(
                displayErrors: true
            );
        }
    }

    /**
     * Bootstrap the timezone.
     */
    protected static function bootstrapTimezone(Valkyrja $config): void
    {
        date_default_timezone_set($config->app->timezone);
    }
}
