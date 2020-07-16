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

namespace Valkyrja\Client\Providers;

use GuzzleHttp\Client as Guzzle;
use Valkyrja\Client\Adapters\GuzzleAdapter;
use Valkyrja\Client\Adapters\LogAdapter;
use Valkyrja\Client\Adapters\NullAdapter;
use Valkyrja\Client\Client;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Log\Logger;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function publishers(): array
    {
        return [
            Client::class        => 'publishClient',
            GuzzleAdapter::class => 'publishGuzzleAdapter',
            NullAdapter::class   => 'publishNullAdapter',
            LogAdapter::class    => 'publishLogAdapter',
        ];
    }

    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function provides(): array
    {
        return [
            Client::class,
            GuzzleAdapter::class,
            LogAdapter::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
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
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Client::class,
            new \Valkyrja\Client\Managers\Client(
                $container,
                (array) $config['client']
            )
        );
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
        $container->setSingleton(
            GuzzleAdapter::class,
            new GuzzleAdapter(
                new Guzzle(),
                $container->getSingleton(ResponseFactory::class)
            )
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
        $container->setSingleton(
            NullAdapter::class,
            new NullAdapter(
                $container->getSingleton(ResponseFactory::class)
            )
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
        $container->setSingleton(
            LogAdapter::class,
            new LogAdapter(
                $container->getSingleton(Logger::class),
                $container->getSingleton(ResponseFactory::class)
            )
        );
    }
}
