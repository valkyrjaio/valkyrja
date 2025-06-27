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

namespace Valkyrja\Http\Client\Provider;

use GuzzleHttp\Client as Guzzle;
use Valkyrja\Application\Config\ValkyrjaConfig;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\Client\Adapter\Contract\Adapter;
use Valkyrja\Http\Client\Adapter\GuzzleAdapter;
use Valkyrja\Http\Client\Adapter\LogAdapter;
use Valkyrja\Http\Client\Adapter\NullAdapter;
use Valkyrja\Http\Client\Config\GuzzleConfiguration;
use Valkyrja\Http\Client\Config\LogConfiguration;
use Valkyrja\Http\Client\Config\NullConfiguration;
use Valkyrja\Http\Client\Contract\Client;
use Valkyrja\Http\Client\Driver\Driver;
use Valkyrja\Http\Client\Factory\ContainerFactory;
use Valkyrja\Http\Client\Factory\Contract\Factory;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
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
     */
    public static function publishClient(Container $container): void
    {
        $config = $container->getSingleton(ValkyrjaConfig::class);

        $container->setSingleton(
            Client::class,
            new \Valkyrja\Http\Client\Client(
                $container->getSingleton(Factory::class),
                $config->client
            )
        );
    }

    /**
     * Publish the factory service.
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
     */
    public static function publishDriver(Container $container): void
    {
        $container->setCallable(
            Driver::class,
            [self::class, 'createDriver']
        );
    }

    /**
     * Create a driver.
     */
    public static function createDriver(Container $container, Adapter $adapter): Driver
    {
        return new Driver($adapter);
    }

    /**
     * Publish the guzzle adapter service.
     */
    public static function publishGuzzleAdapter(Container $container): void
    {
        $container->setCallable(
            GuzzleAdapter::class,
            [self::class, 'createGuzzleAdapter']
        );
    }

    /**
     * Create a guzzle adapter.
     */
    public static function createGuzzleAdapter(Container $container, GuzzleConfiguration $config): GuzzleAdapter
    {
        $responseFactory = $container->getSingleton(ResponseFactory::class);

        return new GuzzleAdapter(
            new Guzzle($config->options),
            $responseFactory,
            $config
        );
    }

    /**
     * Publish the log adapter service.
     */
    public static function publishLogAdapter(Container $container): void
    {
        $container->setCallable(
            LogAdapter::class,
            [self::class, 'createLogAdapter']
        );
    }

    /**
     * Create a log adapter.
     */
    public static function createLogAdapter(Container $container, LogConfiguration $config): LogAdapter
    {
        $logger          = $container->getSingleton(Logger::class);
        $responseFactory = $container->getSingleton(ResponseFactory::class);

        return new LogAdapter(
            $logger->use($config->logger),
            $responseFactory,
            $config
        );
    }

    /**
     * Publish the adapter service.
     */
    public static function publishNullAdapter(Container $container): void
    {
        $container->setCallable(
            NullAdapter::class,
            [self::class, 'createNullAdapter']
        );
    }

    /**
     * Create a null adapter.
     */
    public static function createNullAdapter(Container $container, NullConfiguration $config): NullAdapter
    {
        $responseFactory = $container->getSingleton(ResponseFactory::class);

        return new NullAdapter(
            $responseFactory,
            $config
        );
    }
}
