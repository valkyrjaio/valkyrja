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

use PDO;
use Valkyrja\Orm\Data\Contract\OrmSqliteConfigContract;
use Valkyrja\Orm\Data\OrmSqliteConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class OrmSqliteConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(OrmSqliteConfigContract::class, new OrmSqliteConfig());
    }

    public function testDefaults(): void
    {
        $config = new OrmSqliteConfig();

        self::assertSame('valkyrja', $config->sqliteDb);
        self::assertSame('127.0.0.1', $config->sqliteHost);
        self::assertSame(3306, $config->sqlitePort);
        self::assertSame('valkyrja', $config->sqliteUser);
        self::assertSame('sqlite-password', $config->sqlitePassword);
        self::assertSame('utf8', $config->sqliteCharset);
        self::assertFalse($config->sqliteOptions[PDO::ATTR_EMULATE_PREPARES]);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new OrmSqliteConfig(
            sqliteDb: 'test-db',
            sqliteHost: 'sqlite.test',
            sqlitePort: 3308,
            sqliteUser: 'test-user',
            sqlitePassword: 'test-password',
            sqliteCharset: 'utf8',
            sqliteOptions: [],
        );

        self::assertSame('test-db', $config->sqliteDb);
        self::assertSame('sqlite.test', $config->sqliteHost);
        self::assertSame(3308, $config->sqlitePort);
        self::assertSame('test-user', $config->sqliteUser);
        self::assertSame('test-password', $config->sqlitePassword);
        self::assertSame('utf8', $config->sqliteCharset);
        self::assertSame([], $config->sqliteOptions);
    }
}
