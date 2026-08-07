<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Routing\Provider;

use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Provider\Contract\HttpRouteProviderContract;
use Valkyrja\Tests\Fixtures\Http\Routing\Controller\ControllerFixture;
use Valkyrja\Tests\Fixtures\Http\Routing\Handler\RouteHandlerFixture;

final class RouteProviderFixture implements HttpRouteProviderContract
{
    public static function handler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return new Response();
    }

    #[Override]
    public function getControllerClasses(): array
    {
        return [ControllerFixture::class];
    }

    #[Override]
    public function getRoutes(): array
    {
        return [
            new Route(
                path: '/from-provider',
                name: 'route-from-provider',
                handler: RouteHandlerFixture::handle(...),
            ),
        ];
    }
}
