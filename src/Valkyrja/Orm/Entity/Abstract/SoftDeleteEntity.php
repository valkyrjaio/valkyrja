<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Entity\Abstract;

use Valkyrja\Orm\Entity\Contract\SoftDeleteEntityContract;
use Valkyrja\Orm\Entity\Trait\SoftDeleteFields;

abstract class SoftDeleteEntity extends Entity implements SoftDeleteEntityContract
{
    use SoftDeleteFields;
}
