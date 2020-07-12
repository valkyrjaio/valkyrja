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

namespace Valkyrja\View\Providers;

use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\View\Exceptions\InvalidConfigPath;
use Valkyrja\View\View;

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
            View::class => 'publishView',
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
            View::class,
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
     * Publish the view service.
     *
     * @param Container $container The container
     *
     * @throws InvalidConfigPath
     *
     * @return void
     */
    public static function publishView(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            View::class,
            new \Valkyrja\View\Views\View(
                (array) $config['view']
            )
        );
    }
}
