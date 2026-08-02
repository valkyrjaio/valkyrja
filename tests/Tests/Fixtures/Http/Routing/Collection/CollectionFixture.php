<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Routing\Collection;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Collection\RouteCollection;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

final class CollectionFixture extends RouteCollection
{
    public function setRouteToRequestMethodWrapper(RouteContract $route, RequestMethod $requestMethod): void
    {
        $this->setRouteToRequestMethod($route, $requestMethod);
    }

    public function getRouteFromNameWrapper(string $name): RouteContract
    {
        return $this->getRouteFromName($name);
    }

    public function getDynamicRouteFromNameWrapper(string $name): RouteContract
    {
        return $this->getDynamicRouteFromName($name);
    }
}
