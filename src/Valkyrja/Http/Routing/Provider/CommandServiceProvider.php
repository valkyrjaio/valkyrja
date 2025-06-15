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

namespace Valkyrja\Http\Routing\Provider;

use Valkyrja\Console\Input\Contract\Input;
use Valkyrja\Console\Output\Contract\Output;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\Routing\Command\RoutesList;
use Valkyrja\Http\Routing\Contract\Router;

/**
 * Class CommandServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class CommandServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            RoutesList::class => [self::class, 'publishRoutesList'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            RoutesList::class,
        ];
    }

    /**
     * Publish the routes list command.
     */
    public static function publishRoutesList(Container $container): void
    {
        $container->setSingleton(
            RoutesList::class,
            new RoutesList(
                router: $container->getSingleton(Router::class),
                input: $container->getSingleton(Input::class),
                output: $container->getSingleton(Output::class)
            )
        );
    }
}
