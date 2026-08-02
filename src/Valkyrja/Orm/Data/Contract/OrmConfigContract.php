<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Data\Contract;

use Valkyrja\Orm\Manager\Contract\ManagerContract;

interface OrmConfigContract
{
    /** @var class-string<ManagerContract> */
    public string $defaultManager {
        get;
    }
}
