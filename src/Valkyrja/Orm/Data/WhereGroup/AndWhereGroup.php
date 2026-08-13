<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Data\WhereGroup;

use Valkyrja\Orm\Data\WhereGroup;
use Valkyrja\Orm\Enum\WhereType;

readonly class AndWhereGroup extends WhereGroup
{
    protected const WhereType TYPE = WhereType::AND;
}
