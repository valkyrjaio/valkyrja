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

use PDO;
use Valkyrja\Orm\Data\Contract\OrmMysqlConfigContract;

class OrmMysqlConfig implements OrmMysqlConfigContract
{
    /**
     * @param non-empty-string      $mysqlDb       The database to connect to
     * @param non-empty-string      $mysqlHost     The host to connect to
     * @param positive-int          $mysqlPort     The port to connect to
     * @param non-empty-string      $mysqlUser     The user to connect as
     * @param non-empty-string      $mysqlPassword The password to connect with
     * @param non-empty-string      $mysqlCharset  The character set to connect with
     * @param bool|null             $mysqlStrict   Whether to connect in strict mode
     * @param non-empty-string|null $mysqlEngine   The engine to connect with
     * @param array<int, int|bool>  $mysqlOptions  The options to give the PDO connection
     */
    public function __construct(
        public readonly string $mysqlDb = 'valkyrja',
        public readonly string $mysqlHost = '127.0.0.1',
        public readonly int $mysqlPort = 3306,
        public readonly string $mysqlUser = 'valkyrja',
        public readonly string $mysqlPassword = 'mysql-password',
        public readonly string $mysqlCharset = 'utf8mb4',
        public readonly bool|null $mysqlStrict = null,
        public readonly string|null $mysqlEngine = null,
        public readonly array $mysqlOptions = [
            PDO::ATTR_CASE              => PDO::CASE_NATURAL,
            PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES  => false,
        ],
    ) {
    }
}
