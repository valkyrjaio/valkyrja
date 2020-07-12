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

namespace Valkyrja\HttpKernel\Providers;

use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Event\Events;
use Valkyrja\HttpKernel\Kernel;
use Valkyrja\Routing\Router;

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
            Kernel::class => 'publishKernel',
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
            Kernel::class,
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
     * Publish the kernel service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishKernel(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Kernel::class,
            new \Valkyrja\HttpKernel\Kernels\Kernel(
                $container,
                $container->getSingleton(Events::class),
                $container->getSingleton(Router::class),
                (array) $config['routing'],
                $config['app']['debug']
            )
        );
    }
}
