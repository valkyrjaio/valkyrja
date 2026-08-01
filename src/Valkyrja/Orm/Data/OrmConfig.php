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
