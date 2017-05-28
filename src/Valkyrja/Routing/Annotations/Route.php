<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Annotations;

use Valkyrja\Annotations\Annotatable;
use Valkyrja\Contracts\Annotations\Annotation;
use Valkyrja\Routing\Route as RouterRoute;

/**
 * Class Route.
 *
 * @author Melech Mizrachi
 */
class Route extends RouterRoute implements Annotation
{
    use Annotatable;
}
