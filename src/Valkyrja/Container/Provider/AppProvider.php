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

use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Env;
use Valkyrja\Application\Support\Provider;
use Valkyrja\Config\Config\Config;
use Valkyrja\Config\Config\ValkyrjaDataConfig;
use Valkyrja\Container\CacheableContainer;
use Valkyrja\Container\Config as ContainerConfig;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Exception\Contract\ErrorHandler as ErrorHandlerContract;
use Valkyrja\Exception\ErrorHandler;

/**
 * Class AppProvider.
 *
 * @author Melech Mizrachi
 *
 * @psalm-import-type ConfigAsArray from ContainerConfig
 *
 * @phpstan-import-type ConfigAsArray from ContainerConfig
 */
final class AppProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publish(Application $app): void
    {
        /** @var array{container: ConfigAsArray} $config */
        $config     = $app->config();
        $dataConfig = $app->dataConfig();

        $container = new CacheableContainer($config['container'], $app->debug());

        $app->setContainer($container);

        $container->setup();

        self::bootstrapContainer($app, $container);
        self::bootstrapServices($app, $container);
        // Bootstrap debug capabilities
        self::bootstrapErrorHandler($app, $container);
        // Bootstrap the timezone
        self::bootstrapTimezone($dataConfig);
    }

    /**
     * Bootstrap container.
     */
    protected static function bootstrapContainer(Application $app, Container $container): void
    {
    }

    /**
     * Bootstrap services.
     */
    protected static function bootstrapServices(Application $app, Container $container): void
    {
        $container->setSingleton(Application::class, $app);
        $container->setSingleton('env', $app->env());
        $container->bindAlias('env', Env::class);
        $container->setSingleton(Config::class, $app->config());
        $container->setSingleton(ValkyrjaDataConfig::class, $app->dataConfig());
        $container->bindAlias('config', Config::class);
        $container->setSingleton(Container::class, $container);
    }

    /**
     * Bootstrap error handler.
     */
    protected static function bootstrapErrorHandler(Application $app, Container $container): void
    {
        $config       = $app->dataConfig();
        $errorHandler = $config->app->errorHandler
            ?? ErrorHandler::class;

        // Set error handler in the service container
        $container->setSingleton(ErrorHandlerContract::class, $errorHandler);

        // If debug is on, enable debug handling
        if ($app->debug()) {
            // Enable error handling
            $errorHandler::enable(
                displayErrors: true
            );
        }
    }

    /**
     * Bootstrap the timezone.
     */
    protected static function bootstrapTimezone(ValkyrjaDataConfig $config): void
    {
        date_default_timezone_set($config->app->timezone);
    }
}
