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

namespace Valkyrja\Client\Provider;

use GuzzleHttp\Client as Guzzle;
use Valkyrja\Client\Adapter\Contract\Adapter;
use Valkyrja\Client\Adapter\GuzzleAdapter;
use Valkyrja\Client\Adapter\LogAdapter;
use Valkyrja\Client\Adapter\NullAdapter;
use Valkyrja\Client\Contract\Client;
use Valkyrja\Client\Driver\Driver;
use Valkyrja\Client\Factory\ContainerFactory;
use Valkyrja\Client\Factory\Contract\Factory;
use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Log\Contract\Logger;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Client::class        => [self::class, 'publishClient'],
            Factory::class       => [self::class, 'publishFactory'],
            Driver::class        => [self::class, 'publishDriver'],
            GuzzleAdapter::class => [self::class, 'publishGuzzleAdapter'],
            LogAdapter::class    => [self::class, 'publishLogAdapter'],
            NullAdapter::class   => [self::class, 'publishNullAdapter'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Client::class,
            Factory::class,
            Driver::class,
            GuzzleAdapter::class,
            LogAdapter::class,
            NullAdapter::class,
        ];
    }

    /**
     * Publish the client service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishClient(Container $container): void
    {
        /** @var array{client: \Valkyrja\Client\Config|array<string, mixed>, ...} $config */
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Client::class,
            new \Valkyrja\Client\Client(
                $container->getSingleton(Factory::class),
                $config['client']
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
     * Publish the driver service.
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
     * Create a driver.
     *
     * @param Container $container
     * @param Adapter   $adapter
     *
     * @return Driver
     */
    public static function createDriver(Container $container, Adapter $adapter): Driver
    {
        return new Driver($adapter);
    }

    /**
     * Publish the guzzle adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishGuzzleAdapter(Container $container): void
    {
        $container->setCallable(
            GuzzleAdapter::class,
            [static::class, 'createGuzzleAdapter']
        );
    }

    /**
     * Create a guzzle adapter.
     *
     * @param Container                             $container
     * @param array{options?: array<string, mixed>} $config
     *
     * @return GuzzleAdapter
     */
    public static function createGuzzleAdapter(Container $container, array $config): GuzzleAdapter
    {
        $responseFactory = $container->getSingleton(ResponseFactory::class);

        return new GuzzleAdapter(
            new Guzzle($config['options'] ?? []),
            $responseFactory,
            $config
        );
    }

    /**
     * Publish the log adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLogAdapter(Container $container): void
    {
        $container->setCallable(
            LogAdapter::class,
            [static::class, 'createLogAdapter']
        );
    }

    /**
     * Create a log adapter.
     *
     * @param Container              $container
     * @param array{logger?: string} $config
     *
     * @return LogAdapter
     */
    public static function createLogAdapter(Container $container, array $config): LogAdapter
    {
        $logger          = $container->getSingleton(Logger::class);
        $responseFactory = $container->getSingleton(ResponseFactory::class);

        return new LogAdapter(
            $logger->use($config['logger'] ?? null),
            $responseFactory,
            $config
        );
    }

    /**
     * Publish the adapter service.
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
     * Create a null adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return NullAdapter
     */
    public static function createNullAdapter(Container $container, array $config): NullAdapter
    {
        $responseFactory = $container->getSingleton(ResponseFactory::class);

        return new NullAdapter(
            $responseFactory,
            $config
        );
    }
}
