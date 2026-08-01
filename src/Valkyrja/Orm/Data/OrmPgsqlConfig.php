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
use Valkyrja\Orm\Data\Contract\OrmPgsqlConfigContract;

class OrmPgsqlConfig implements OrmPgsqlConfigContract
{
    /**
     * @param non-empty-string     $pgsqlDb       The database to connect to
     * @param non-empty-string     $pgsqlHost     The host to connect to
     * @param positive-int         $pgsqlPort     The port to connect to
     * @param non-empty-string     $pgsqlUser     The user to connect as
     * @param non-empty-string     $pgsqlPassword The password to connect with
     * @param non-empty-string     $pgsqlCharset  The character set to connect with
     * @param non-empty-string     $pgsqlSchema   The schema to search in
     * @param non-empty-string     $pgsqlSslMode  The ssl mode to connect with
     * @param array<int, int|bool> $pgsqlOptions  The options to give the PDO connection
     */
    public function __construct(
        public readonly string $pgsqlDb = 'valkyrja',
        public readonly string $pgsqlHost = '127.0.0.1',
        public readonly int $pgsqlPort = 6379,
        public readonly string $pgsqlUser = 'valkyrja',
        public readonly string $pgsqlPassword = 'pgsql-password',
        public readonly string $pgsqlCharset = 'utf8',
        public readonly string $pgsqlSchema = 'public',
        public readonly string $pgsqlSslMode = 'prefer',
        public readonly array $pgsqlOptions = [
            PDO::ATTR_PERSISTENT        => true,
            PDO::ATTR_CASE              => PDO::CASE_NATURAL,
            PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
            PDO::ATTR_STRINGIFY_FETCHES => false,
        ],
    ) {
    }
}
