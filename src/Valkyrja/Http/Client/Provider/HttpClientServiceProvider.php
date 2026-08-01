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
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Client\Data\Contract\HttpClientConfigContract;
use Valkyrja\Http\Client\Data\HttpClientConfig;
use Valkyrja\Http\Client\Manager\Contract\ClientContract;
use Valkyrja\Http\Client\Manager\GuzzleClient;
use Valkyrja\Http\Client\Manager\LogClient;
use Valkyrja\Http\Client\Manager\NullClient;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;

class HttpClientServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the http client config service.
     */
    public static function publishConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof HttpClientConfigContract) {
            $container->setSingleton(HttpClientConfigContract::class, $config);

            return;
        }

        $container->setSingleton(
            HttpClientConfigContract::class,
            new HttpClientConfig()
        );
    }

    /**
     * Publish the client service.
     */
    public static function publishClient(ContainerContract $container): void
    {
        $config = $container->getSingleton(HttpClientConfigContract::class);

        $container->setSingleton(
            ClientContract::class,
            $container->getSingleton($config->defaultClient)
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

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            HttpClientConfigContract::class => [self::class, 'publishConfig'],
            ClientContract::class           => [self::class, 'publishClient'],
            GuzzleClient::class             => [self::class, 'publishGuzzleClient'],
            Client::class                   => [self::class, 'publishGuzzle'],
            LogClient::class                => [self::class, 'publishLogClient'],
            NullClient::class               => [self::class, 'publishNullClient'],
        ];
    }
}
