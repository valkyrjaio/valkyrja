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

namespace Valkyrja\Event\Providers;

use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Dispatchers\CacheableEvents;
use Valkyrja\Event\Events as Contract;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * Whether this provider is deferred.
     *
     * @return bool
     */
    public static function deferred(): bool
    {
        return false;
    }

    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function provides(): array
    {
        return [];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Contract::class,
            $events = new CacheableEvents(
                $container,
                $container->getSingleton(Dispatcher::class),
                (array) $config['event']
            )
        );

        $events->setup();
    }
}
