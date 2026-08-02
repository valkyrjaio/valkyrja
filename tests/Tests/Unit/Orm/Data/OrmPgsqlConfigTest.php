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
use Valkyrja\Orm\Data\Contract\OrmPgsqlConfigContract;
use Valkyrja\Orm\Data\OrmPgsqlConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class OrmPgsqlConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(OrmPgsqlConfigContract::class, new OrmPgsqlConfig());
    }

    public function testDefaults(): void
    {
        $config = new OrmPgsqlConfig();

        self::assertSame('valkyrja', $config->pgsqlDb);
        self::assertSame('127.0.0.1', $config->pgsqlHost);
        self::assertSame(6379, $config->pgsqlPort);
        self::assertSame('valkyrja', $config->pgsqlUser);
        self::assertSame('pgsql-password', $config->pgsqlPassword);
        self::assertSame('utf8', $config->pgsqlCharset);
        self::assertSame('public', $config->pgsqlSchema);
        self::assertSame('prefer', $config->pgsqlSslMode);
        self::assertTrue($config->pgsqlOptions[PDO::ATTR_PERSISTENT]);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new OrmPgsqlConfig(
            pgsqlDb: 'test-db',
            pgsqlHost: 'pgsql.test',
            pgsqlPort: 5432,
            pgsqlUser: 'test-user',
            pgsqlPassword: 'test-password',
            pgsqlCharset: 'utf8',
            pgsqlSchema: 'test',
            pgsqlSslMode: 'require',
            pgsqlOptions: [],
        );

        self::assertSame('test-db', $config->pgsqlDb);
        self::assertSame('pgsql.test', $config->pgsqlHost);
        self::assertSame(5432, $config->pgsqlPort);
        self::assertSame('test-user', $config->pgsqlUser);
        self::assertSame('test-password', $config->pgsqlPassword);
        self::assertSame('utf8', $config->pgsqlCharset);
        self::assertSame('test', $config->pgsqlSchema);
        self::assertSame('require', $config->pgsqlSslMode);
        self::assertSame([], $config->pgsqlOptions);
    }
}
