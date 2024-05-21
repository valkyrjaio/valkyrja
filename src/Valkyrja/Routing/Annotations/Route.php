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

namespace Valkyrja\Routing\Annotations;

use Valkyrja\Annotation\Model\Annotatable;
use Valkyrja\Annotation\Model\Contract\Annotation;
use Valkyrja\Routing\Models\Route as RouteModel;

/**
 * Class Route.
 *
 * @author Melech Mizrachi
 */
class Route extends RouteModel implements Annotation
{
    use Annotatable;
}
