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

namespace Valkyrja\Http\Server\Provider;

use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Event\Contract\Dispatcher as EventDispatcher;
use Valkyrja\Http\Server\Contract\RequestHandler;
use Valkyrja\Routing\Contract\Router;

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
            RequestHandler::class => [self::class, 'publishRequestHandler'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            RequestHandler::class,
        ];
    }

    /**
     * Publish the RequestHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRequestHandler(Container $container): void
    {
        $config         = $container->getSingleton(Config::class);
        $requestHandler = $config['app']['httpKernel'] ?? \Valkyrja\Http\Server\RequestHandler::class;

        $container->setSingleton(
            RequestHandler::class,
            new $requestHandler(
                $container,
                $container->getSingleton(EventDispatcher::class),
                $container->getSingleton(Router::class),
                $config['routing'],
                $config['app']['debug']
            )
        );
    }
}
