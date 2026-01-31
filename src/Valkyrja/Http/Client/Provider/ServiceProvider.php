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

use GuzzleHttp\Client;
use Override;
use Valkyrja\Application\Env\Env;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Http\Client\Manager\Contract\ClientContract;
use Valkyrja\Http\Client\Manager\GuzzleClient;
use Valkyrja\Http\Client\Manager\LogClient;
use Valkyrja\Http\Client\Manager\NullClient;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            ClientContract::class => [self::class, 'publishClient'],
            GuzzleClient::class   => [self::class, 'publishGuzzleClient'],
            Client::class         => [self::class, 'publishGuzzle'],
            LogClient::class      => [self::class, 'publishLogClient'],
            NullClient::class     => [self::class, 'publishNullClient'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            ClientContract::class,
            GuzzleClient::class,
            Client::class,
            LogClient::class,
            NullClient::class,
        ];
    }

    /**
     * Publish the client service.
     */
    public static function publishClient(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<ClientContract> $default */
        $default = $env::HTTP_CLIENT_DEFAULT
            ?? GuzzleClient::class;

        $container->setSingleton(
            ClientContract::class,
            $container->getSingleton($default)
        );
    }

    /**
     * Publish the GuzzleClient service.
     */
    public static function publishGuzzleClient(ContainerContract $container): void
    {
        $container->setSingleton(
            GuzzleClient::class,
            new GuzzleClient(
                client: $container->getSingleton(Client::class),
                responseFactory: $container->getSingleton(ResponseFactoryContract::class),
            )
        );
    }

    /**
     * Publish the LogClient service.
     */
    public static function publishLogClient(ContainerContract $container): void
    {
        $container->setSingleton(
            LogClient::class,
            new LogClient(
                logger: $container->getSingleton(LoggerContract::class),
            )
        );
    }

    /**
     * Publish the NullClient service.
     */
    public static function publishNullClient(ContainerContract $container): void
    {
        $container->setSingleton(
            NullClient::class,
            new NullClient()
        );
    }

    /**
     * Publish the Client service.
     */
    public static function publishGuzzle(ContainerContract $container): void
    {
        $container->setSingleton(
            Client::class,
            new Client()
        );
    }
}
