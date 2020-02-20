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

namespace Valkyrja\Routing\Annotation;

use Valkyrja\Annotation\Annotation;
use \Valkyrja\Routing\Route as RouteModel;

/**
 * Interface Route.
 *
 * @author Melech Mizrachi
 */
interface Route extends Annotation, RouteModel
{
}
