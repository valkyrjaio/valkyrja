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

namespace Valkyrja\Api\Provider;

use Override;
use Valkyrja\Api\Manager\Contract\Api;
use Valkyrja\Application\Env;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;

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
    #[Override]
    public static function publishers(): array
    {
        return [
            Api::class => [self::class, 'publishApi'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Api::class,
        ];
    }

    /**
     * Publish the api service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishApi(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var bool $debugMode */
        $debugMode = $env::APP_DEBUG_MODE;

        $container->setSingleton(
            Api::class,
            new \Valkyrja\Api\Manager\Api(
                responseFactory: $container->getSingleton(ResponseFactory::class),
                debug: $debugMode
            )
        );
    }
}
