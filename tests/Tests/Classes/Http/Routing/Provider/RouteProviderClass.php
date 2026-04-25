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

namespace Valkyrja\Tests\Classes\Http\Routing\Provider;

use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Provider\Contract\HttpRouteProviderContract;

final class RouteProviderClass implements HttpRouteProviderContract
{
    #[Override]
    public static function getControllerClasses(): array
    {
        return ['AControllerClass'];
    }

    #[Override]
    public static function getRoutes(): array
    {
        return [
            new Route(
                path: '/from-provider',
                name: 'route-from-provider',
                handler: static fn (): null => null,
            ),
        ];
    }

    public static function handler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return new Response();
    }
}
