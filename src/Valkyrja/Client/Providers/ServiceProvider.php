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
use Valkyrja\Client\Client;
use Valkyrja\Client\Clients\GuzzleAdapter;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;

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
    public static function GuzzleAdapter(Container $container): void
    {
        $container->setSingleton(
            GuzzleAdapter::class,
            new GuzzleAdapter(
                new Guzzle()
            )
        );
    }
}
