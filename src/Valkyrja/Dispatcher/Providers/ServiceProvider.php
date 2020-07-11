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

namespace Valkyrja\Dispatcher\Providers;

use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Dispatcher as Contract;
use Valkyrja\Dispatcher\Dispatchers\Dispatcher;

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
        $container->setSingleton(
            Contract::class,
            new Dispatcher($container)
        );
    }
}
