<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Data;

use Valkyrja\Orm\Data\Contract\OrmConfigContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Manager\MysqlManager;

class OrmConfig implements OrmConfigContract
{
    /**
     * @param class-string<ManagerContract> $defaultManager The manager to use by default
     */
    public function __construct(
        public readonly string $defaultManager = MysqlManager::class,
    ) {
    }
}
