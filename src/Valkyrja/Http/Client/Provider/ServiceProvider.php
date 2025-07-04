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
use Valkyrja\Application\Env;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\Client\Contract\Client;
use Valkyrja\Http\Client\GuzzleClient;
use Valkyrja\Http\Client\LogClient;
use Valkyrja\Http\Client\NullClient;
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
            Client::class       => [self::class, 'publishClient'],
            GuzzleClient::class => [self::class, 'publishGuzzleClient'],
            Guzzle::class       => [self::class, 'publishGuzzle'],
            LogClient::class    => [self::class, 'publishLogClient'],
            NullClient::class   => [self::class, 'publishNullClient'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Client::class,
            GuzzleClient::class,
            Guzzle::class,
            LogClient::class,
            NullClient::class,
        ];
    }

    /**
     * Publish the client service.
     */
    public static function publishClient(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<Client> $default */
        $default = $env::HTTP_CLIENT_DEFAULT;

        $container->setSingleton(
            Client::class,
            $container->getSingleton($default)
        );
    }

    /**
     * Publish the GuzzleClient service.
     */
    public static function publishGuzzleClient(Container $container): void
    {
        $container->setSingleton(
            GuzzleClient::class,
            new GuzzleClient(
                $container->getSingleton(Guzzle::class),
                $container->getSingleton(ResponseFactory::class),
            )
        );
    }

    /**
     * Publish the LogClient service.
     */
    public static function publishLogClient(Container $container): void
    {
        $container->setSingleton(
            LogClient::class,
            new LogClient(
                $container->getSingleton(Logger::class),
                $container->getSingleton(ResponseFactory::class),
            )
        );
    }

    /**
     * Publish the NullClient service.
     */
    public static function publishNullClient(Container $container): void
    {
        $container->setSingleton(
            NullClient::class,
            new NullClient(
                $container->getSingleton(ResponseFactory::class),
            )
        );
    }

    /**
     * Publish the Guzzle service.
     */
    public static function publishGuzzle(Container $container): void
    {
        $container->setSingleton(
            Guzzle::class,
            new Guzzle()
        );
    }
}
