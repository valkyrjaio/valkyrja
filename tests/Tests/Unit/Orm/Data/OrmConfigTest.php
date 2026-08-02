<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Orm\Data;

use Valkyrja\Orm\Data\Contract\OrmConfigContract;
use Valkyrja\Orm\Data\OrmConfig;
use Valkyrja\Orm\Manager\MysqlManager;
use Valkyrja\Orm\Manager\SqliteManager;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class OrmConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(OrmConfigContract::class, new OrmConfig());
    }

    public function testDefaults(): void
    {
        self::assertSame(MysqlManager::class, new OrmConfig()->defaultManager);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame(
            SqliteManager::class,
            new OrmConfig(defaultManager: SqliteManager::class)->defaultManager
        );
    }
}
