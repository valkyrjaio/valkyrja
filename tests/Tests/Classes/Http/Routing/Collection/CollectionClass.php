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

namespace Valkyrja\Tests\Classes\Http\Routing\Collection;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Collection\Collection;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

final class CollectionClass extends Collection
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
