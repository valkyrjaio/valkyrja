<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Routing\Provider;

use Override;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Provider\Contract\CliRouteProviderContract;
use Valkyrja\Tests\Fixtures\Cli\Routing\Controller\ControllerFixture;
use Valkyrja\Tests\Fixtures\Cli\Routing\Handler\RouteHandlerFixture;

final class RouteProviderFixture implements CliRouteProviderContract
{
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
                name: 'test-provider',
                description: 'test',
                handler: RouteHandlerFixture::handle(...),
            ),
        ];
    }
}
