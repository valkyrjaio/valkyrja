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

use Valkyrja\Orm\Entity\Contract\DatedEntityContract;
use Valkyrja\Orm\Entity\Trait\DatedFields;

abstract class DatedEntity extends Entity implements DatedEntityContract
{
    use DatedFields;
}
