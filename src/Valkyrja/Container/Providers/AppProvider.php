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

use JsonException;
use Valkyrja\Application\Application;
use Valkyrja\Application\Support\Provider;
use Valkyrja\Container\Container as Contract;
use Valkyrja\Container\Managers\CacheableContainer;
use Valkyrja\Support\Type\Arr;
use Valkyrja\Support\Type\Obj;

use function is_array;

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
     * @throws JsonException
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $config = $app->config();

        if (! is_array($config)) {
            $config = Arr::fromString(Obj::toString($config));
        }

        $container = new CacheableContainer($config['container'], $app->debug());

        $app->setContainer($container);

        $container->setSingleton(Application::class, $app);
        $container->setSingleton('env', $app->env());
        $container->setSingleton('config', $config);
        $container->setSingleton(Contract::class, $container);
        $container->setup();
    }
}
