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

namespace Valkyrja\Container\Providers;

use Valkyrja\Application\Application;
use Valkyrja\Application\Support\Provider;
use Valkyrja\Container\Container as Contract;
use Valkyrja\Container\Dispatchers\CacheableContainer;

/**
 * Class AppProvider.
 *
 * @author Melech Mizrachi
 */
class AppProvider extends Provider
{
    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $config = $app->config();

        $container = new CacheableContainer((array) $config['container'], $app->debug());

        $app->setContainer($container);

        $container->setSingleton(Application::class, $app);
        $container->setSingleton('env', $app->env());
        $container->setSingleton('config', $config);
        $container->setSingleton(Contract::class, $container);
        $container->setup();
    }
}