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

namespace Valkyrja\Log\Provider;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;
use Psr\Log\LoggerInterface;
use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Adapter\Contract\Adapter;
use Valkyrja\Log\Adapter\NullAdapter;
use Valkyrja\Log\Adapter\PsrAdapter;
use Valkyrja\Log\Constant\LogLevel;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Log\Driver\Driver;
use Valkyrja\Log\Factory\ContainerFactory;
use Valkyrja\Log\Factory\Contract\Factory;

use function date;

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
            Logger::class          => [self::class, 'publishLogger'],
            Factory::class         => [self::class, 'publishFactory'],
            Driver::class          => [self::class, 'publishDriver'],
            NullAdapter::class     => [self::class, 'publishNullAdapter'],
            PsrAdapter::class      => [self::class, 'publishPsrAdapter'],
            LoggerInterface::class => [self::class, 'publishLoggerInterface'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Logger::class,
            Factory::class,
            Driver::class,
            NullAdapter::class,
            PsrAdapter::class,
            LoggerInterface::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function publish(Container $container): void
    {
    }

    /**
     * Publish the logger service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLogger(Container $container): void
    {
        /** @var array{log: \Valkyrja\Log\Config|array<string, mixed>, ...} $config */
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Logger::class,
            new \Valkyrja\Log\Logger(
                $container->getSingleton(Factory::class),
                $config['log']
            )
        );
    }

    /**
     * Publish the factory service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFactory(Container $container): void
    {
        $container->setSingleton(
            Factory::class,
            new ContainerFactory($container),
        );
    }

    /**
     * Publish the default driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDriver(Container $container): void
    {
        $container->setCallable(
            Driver::class,
            [static::class, 'createDriver']
        );
    }

    /**
     * Create the driver.
     *
     * @param Container $container
     * @param Adapter   $adapter
     *
     * @return Driver
     */
    public static function createDriver(Container $container, Adapter $adapter): Driver
    {
        return new Driver(
            $adapter
        );
    }

    /**
     * Publish the psr adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPsrAdapter(Container $container): void
    {
        $container->setCallable(
            PsrAdapter::class,
            [static::class, 'createPsrAdapter']
        );
    }

    /**
     * Create the psr adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return PsrAdapter
     */
    public static function createPsrAdapter(Container $container, array $config): PsrAdapter
    {
        return new PsrAdapter(
            $container->get(
                LoggerInterface::class,
                [
                    $config,
                ]
            ),
            $config
        );
    }

    /**
     * Publish the null adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNullAdapter(Container $container): void
    {
        $container->setCallable(
            NullAdapter::class,
            [static::class, 'createNullAdapter']
        );
    }

    /**
     * Create the null adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return NullAdapter
     */
    public static function createNullAdapter(Container $container, array $config): NullAdapter
    {
        return new NullAdapter(
            $config
        );
    }

    /**
     * Publish the logger interface.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLoggerInterface(Container $container): void
    {
        $container->setCallable(
            LoggerInterface::class,
            [static::class, 'createLoggerInterface']
        );
    }

    /**
     * Create the logger interface.
     *
     * @param array{filePath: string, name: string} $config
     *
     * @return LoggerInterface
     */
    public static function createLoggerInterface(array $config): LoggerInterface
    {
        $filePath  = $config['filePath'];
        $name      = $config['name'] . date('-Y-m-d');
        $handler   = new StreamHandler(
            "$filePath/$name.log",
            LogLevel::DEBUG
        );
        $formatter = new LineFormatter(
            null,
            null,
            true,
            true
        );

        $handler->setFormatter($formatter);

        return new Monolog(
            $name,
            [
                $handler,
            ]
        );
    }
}
