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

namespace Valkyrja\Tests\Fixtures\Orm\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Orm\Data\Contract\OrmConfigContract;
use Valkyrja\Orm\Data\Contract\OrmMysqlConfigContract;
use Valkyrja\Orm\Data\Contract\OrmPgsqlConfigContract;
use Valkyrja\Orm\Data\Contract\OrmSqliteConfigContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Manager\SqliteManager;

/**
 * An application config that implements every orm contract at once.
 *
 * The connection contracts prefix each property with the connection name, so one
 * class can carry the settings for several connections without a name collision.
 */
final class OrmConfigFixture extends Config implements OrmConfigContract, OrmMysqlConfigContract, OrmPgsqlConfigContract, OrmSqliteConfigContract
{
    /**
     * @param class-string<ManagerContract> $defaultManager
     * @param non-empty-string              $mysqlDb
     * @param non-empty-string              $mysqlHost
     * @param positive-int                  $mysqlPort
     * @param non-empty-string              $mysqlUser
     * @param non-empty-string              $mysqlPassword
     * @param non-empty-string              $mysqlCharset
     * @param non-empty-string|null         $mysqlEngine
     * @param array<int, int|bool>          $mysqlOptions
     * @param non-empty-string              $pgsqlDb
     * @param non-empty-string              $pgsqlHost
     * @param positive-int                  $pgsqlPort
     * @param non-empty-string              $pgsqlUser
     * @param non-empty-string              $pgsqlPassword
     * @param non-empty-string              $pgsqlCharset
     * @param non-empty-string              $pgsqlSchema
     * @param non-empty-string              $pgsqlSslMode
     * @param array<int, int|bool>          $pgsqlOptions
     * @param non-empty-string              $sqliteDb
     * @param non-empty-string              $sqliteHost
     * @param positive-int                  $sqlitePort
     * @param non-empty-string              $sqliteUser
     * @param non-empty-string              $sqlitePassword
     * @param non-empty-string              $sqliteCharset
     * @param array<int, int|bool>          $sqliteOptions
     */
    public function __construct(
        public string $defaultManager = SqliteManager::class,
        public string $mysqlDb = 'test-mysql-db',
        public string $mysqlHost = 'mysql.test',
        public int $mysqlPort = 3307,
        public string $mysqlUser = 'test-mysql-user',
        public string $mysqlPassword = 'test-mysql-password',
        public string $mysqlCharset = 'utf8',
        public bool|null $mysqlStrict = true,
        public string|null $mysqlEngine = 'InnoDB',
        public array $mysqlOptions = [],
        public string $pgsqlDb = 'test-pgsql-db',
        public string $pgsqlHost = 'pgsql.test',
        public int $pgsqlPort = 5432,
        public string $pgsqlUser = 'test-pgsql-user',
        public string $pgsqlPassword = 'test-pgsql-password',
        public string $pgsqlCharset = 'utf8',
        public string $pgsqlSchema = 'test',
        public string $pgsqlSslMode = 'require',
        public array $pgsqlOptions = [],
        public string $sqliteDb = 'test-sqlite-db',
        public string $sqliteHost = 'sqlite.test',
        public int $sqlitePort = 3308,
        public string $sqliteUser = 'test-sqlite-user',
        public string $sqlitePassword = 'test-sqlite-password',
        public string $sqliteCharset = 'utf8',
        public array $sqliteOptions = [],
    ) {
        parent::__construct();
    }
}
