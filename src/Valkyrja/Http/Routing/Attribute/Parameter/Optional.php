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

namespace Valkyrja\Http\Routing\Attribute\Parameter;

use Attribute;
use Valkyrja\Http\Routing\Attribute\Parameter;

/**
 * Attribute Optional.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class Optional extends Parameter
{
}
