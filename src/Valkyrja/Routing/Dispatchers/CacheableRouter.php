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

namespace Valkyrja\Routing\Dispatchers;

use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Events;
use Valkyrja\Http\Request;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Routing\Collections\CacheableCollection;
use Valkyrja\Routing\Matchers\Matcher as MatcherClass;
use Valkyrja\Routing\Router as Contract;

/**
 * Class CacheableRouter.
 *
 * @author Melech Mizrachi
 */
class CacheableRouter extends Router
{
    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
        $config        = $container->getSingleton('config');
        $routingConfig = (array) $config['routing'];

        $container->setSingleton(
            Contract::class,
            $router = new static(
                $container->getSingleton(Container::class),
                $dispatcher = $container->getSingleton(Dispatcher::class),
                $container->getSingleton(Events::class),
                $container->getSingleton(PathGenerator::class),
                $container->getSingleton(Request::class),
                $container->getSingleton(ResponseFactory::class),
                $collection = new CacheableCollection($container, $dispatcher, new MatcherClass(), $routingConfig),
                $routingConfig
            )
        );

        $collection->setup();
    }
}
