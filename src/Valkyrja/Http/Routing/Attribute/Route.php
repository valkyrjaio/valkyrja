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

namespace Valkyrja\Http\Routing\Attribute;

use Attribute;
use Valkyrja\Http\Routing\Data\Route as ParentRoute;

/**
 * Attribute Route.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route extends ParentRoute
{
}
