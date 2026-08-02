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

use PDO;
use Valkyrja\Orm\Data\Contract\OrmSqliteConfigContract;

class OrmSqliteConfig implements OrmSqliteConfigContract
{
    /**
     * @param non-empty-string     $sqliteDb       The database to connect to
     * @param non-empty-string     $sqliteHost     The host to connect to
     * @param positive-int         $sqlitePort     The port to connect to
     * @param non-empty-string     $sqliteUser     The user to connect as
     * @param non-empty-string     $sqlitePassword The password to connect with
     * @param non-empty-string     $sqliteCharset  The character set to connect with
     * @param array<int, int|bool> $sqliteOptions  The options to give the PDO connection
     */
    public function __construct(
        public readonly string $sqliteDb = 'valkyrja',
        public readonly string $sqliteHost = '127.0.0.1',
        public readonly int $sqlitePort = 3306,
        public readonly string $sqliteUser = 'valkyrja',
        public readonly string $sqlitePassword = 'sqlite-password',
        public readonly string $sqliteCharset = 'utf8',
        public readonly array $sqliteOptions = [
            PDO::ATTR_CASE              => PDO::CASE_NATURAL,
            PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES  => false,
        ],
    ) {
    }
}
