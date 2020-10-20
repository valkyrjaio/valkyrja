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

namespace Valkyrja\Routing\Middleware;

use Valkyrja\Routing\Route;
use Valkyrja\Routing\Support\Middleware;

/**
 * Class RouteMiddleware.
 *
 * @author Melech Mizrachi
 */
class RouteMiddleware extends Middleware
{
    public static Route $route;
}
