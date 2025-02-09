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
use Valkyrja\Container\CacheableContainer;
use Valkyrja\Container\Config as ContainerConfig;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Exception\Contract\ErrorHandler as ErrorHandlerContract;
use Valkyrja\Exception\ErrorHandler;

use const E_ALL;

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
        $config = $app->config();

        $container = new CacheableContainer($config['container'], $app->debug());

        $app->setContainer($container);

        $container->setup();

        static::bootstrapContainer($app, $container);
        static::bootstrapServices($app, $container, $config);
        // Bootstrap debug capabilities
        static::bootstrapErrorHandler($app, $container, $config);
        // Bootstrap the timezone
        static::bootstrapTimezone($config);
    }

    /**
     * Bootstrap container.
     *
     * @param Application $app       The application
     * @param Container   $container The container
     *
     * @return void
     */
    protected static function bootstrapContainer(Application $app, Container $container): void
    {
    }

    /**
     * Bootstrap services.
     *
     * @param Application                 $app       The application
     * @param Container                   $container The container
     * @param Config|array<string, mixed> $config    The config
     *
     * @return void
     */
    protected static function bootstrapServices(Application $app, Container $container, Config|array $config): void
    {
        $container->setSingleton(Application::class, $app);
        $container->setSingleton('env', $app->env());
        $container->bindAlias('env', Env::class);
        $container->setSingleton(Config::class, $config);
        $container->bindAlias('config', Config::class);
        $container->setSingleton(Container::class, $container);
    }

    /**
     * Bootstrap error handler.
     *
     * @param Application                 $app       The application
     * @param Container                   $container The container
     * @param Config|array<string, mixed> $config    The config
     *
     * @return void
     */
    protected static function bootstrapErrorHandler(
        Application $app,
        Container $container,
        Config|array $config
    ): void {
        /** @var ErrorHandlerContract $errorHandler */
        $errorHandler = $config['app']['errorHandler']
            ?? ErrorHandler::class;

        // Set error handler in the service container
        $container->setSingleton(ErrorHandlerContract::class, $errorHandler);

        // If debug is on, enable debug handling
        if ($app->debug()) {
            // Enable error handling
            $errorHandler::enable(E_ALL, true);
        }
    }

    /**
     * Bootstrap the timezone.
     *
     * @param Config|array<string, mixed> $config The config
     *
     * @return void
     */
    protected static function bootstrapTimezone(Config|array $config): void
    {
        date_default_timezone_set($config['app']['timezone']);
    }
}
