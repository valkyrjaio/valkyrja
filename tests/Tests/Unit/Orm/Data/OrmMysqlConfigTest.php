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
use Valkyrja\Orm\Data\Contract\OrmMysqlConfigContract;
use Valkyrja\Orm\Data\OrmMysqlConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class OrmMysqlConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(OrmMysqlConfigContract::class, new OrmMysqlConfig());
    }

    public function testDefaults(): void
    {
        $config = new OrmMysqlConfig();

        self::assertSame('valkyrja', $config->mysqlDb);
        self::assertSame('127.0.0.1', $config->mysqlHost);
        self::assertSame(3306, $config->mysqlPort);
        self::assertSame('valkyrja', $config->mysqlUser);
        self::assertSame('mysql-password', $config->mysqlPassword);
        self::assertSame('utf8mb4', $config->mysqlCharset);
        self::assertNull($config->mysqlStrict);
        self::assertNull($config->mysqlEngine);
        self::assertSame(PDO::CASE_NATURAL, $config->mysqlOptions[PDO::ATTR_CASE]);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new OrmMysqlConfig(
            mysqlDb: 'test-db',
            mysqlHost: 'mysql.test',
            mysqlPort: 3307,
            mysqlUser: 'test-user',
            mysqlPassword: 'test-password',
            mysqlCharset: 'utf8',
            mysqlStrict: true,
            mysqlEngine: 'InnoDB',
            mysqlOptions: [],
        );

        self::assertSame('test-db', $config->mysqlDb);
        self::assertSame('mysql.test', $config->mysqlHost);
        self::assertSame(3307, $config->mysqlPort);
        self::assertSame('test-user', $config->mysqlUser);
        self::assertSame('test-password', $config->mysqlPassword);
        self::assertSame('utf8', $config->mysqlCharset);
        self::assertTrue($config->mysqlStrict);
        self::assertSame('InnoDB', $config->mysqlEngine);
        self::assertSame([], $config->mysqlOptions);
    }
}
