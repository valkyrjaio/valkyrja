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

namespace Valkyrja\Routing\Provider;

use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Event\Contract\Dispatcher as Events;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Routing\Collection\Contract\Collection;
use Valkyrja\Routing\Contract\Router;
use Valkyrja\Routing\Matcher\Contract\Matcher;
use Valkyrja\Routing\MessageCapableRouter;
use Valkyrja\Validation\Contract\Validation;

/**
 * Class MessageRouterServiceProvider.
 *
 * @author Melech Mizrachi
 */
class MessageRouterServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Router::class => [self::class, 'publishRouter'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Router::class,
        ];
    }

    /**
     * Publish the router service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRouter(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Router::class,
            new MessageCapableRouter(
                $container->getSingleton(Validation::class),
                $container->getSingleton(Collection::class),
                $container->getSingleton(Container::class),
                $container->getSingleton(Dispatcher::class),
                $container->getSingleton(Events::class),
                $container->getSingleton(Matcher::class),
                $container->getSingleton(ResponseFactory::class),
                $config['routing'],
                $config['app']['debug']
            )
        );
    }
}
