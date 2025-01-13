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
use Valkyrja\Http\Routing\Model\Parameter\Parameter as Model;

/**
 * Attribute Parameter.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class Parameter extends Model
{
}
