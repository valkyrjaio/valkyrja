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

namespace Valkyrja\Orm\Attribute;

use Attribute;
use Valkyrja\Orm\Data\EntityCast as ParentEntityCast;

/**
 * Attribute EntityCast.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class EntityCast extends ParentEntityCast
{
}
