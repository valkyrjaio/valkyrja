<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Annotation\Models;

use Valkyrja\Annotation\Models\Annotation;
use Valkyrja\Routing\Annotation\Route as RouteContract;
use Valkyrja\Routing\Models\Routable;

/**
 * Class Route.
 *
 * @author Melech Mizrachi
 */
class Route extends Annotation implements RouteContract
{
    use Routable;
}
