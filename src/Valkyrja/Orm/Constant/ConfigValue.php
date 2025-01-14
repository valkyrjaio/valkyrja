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

namespace Valkyrja\Orm\Constant;

use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Orm\Adapter\PdoAdapter;
use Valkyrja\Orm\Driver\Driver;
use Valkyrja\Orm\Driver\PgSqlDriver;
use Valkyrja\Orm\Pdo\MySqlPdo;
use Valkyrja\Orm\Pdo\PgSqlPdo;
use Valkyrja\Orm\Persister\Persister;
use Valkyrja\Orm\Query\Query;
use Valkyrja\Orm\QueryBuilder\SqlQueryBuilder;
use Valkyrja\Orm\Repository\Repository;
use Valkyrja\Orm\Retriever\Retriever;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT       = CKP::MYSQL;
    public const ADAPTER       = PdoAdapter::class;
    public const DRIVER        = Driver::class;
    public const QUERY         = Query::class;
    public const QUERY_BUILDER = SqlQueryBuilder::class;
    public const PERSISTER     = Persister::class;
    public const RETRIEVER     = Retriever::class;
    public const REPOSITORY    = Repository::class;
    public const CONNECTIONS   = [
        CKP::MYSQL => [
            CKP::ADAPTER       => null,
            CKP::DRIVER        => null,
            CKP::REPOSITORY    => null,
            CKP::QUERY         => null,
            CKP::QUERY_BUILDER => null,
            CKP::PERSISTER     => null,
            CKP::RETRIEVER     => null,
            CKP::CONFIG        => [
                CKP::PDO      => MySqlPdo::class,
                CKP::DRIVER   => CKP::MYSQL,
                CKP::HOST     => '127.0.0.1',
                CKP::PORT     => '3306',
                CKP::DB       => CKP::VALHALLA,
                CKP::USER     => CKP::VALHALLA,
                CKP::PASSWORD => '',
                CKP::CHARSET  => 'utf8mb4',
                CKP::STRICT   => true,
                CKP::ENGINE   => null,
                CKP::OPTIONS  => null,
            ],
        ],
        CKP::PGSQL => [
            CKP::ADAPTER       => null,
            CKP::DRIVER        => PgSqlDriver::class,
            CKP::REPOSITORY    => null,
            CKP::QUERY         => null,
            CKP::QUERY_BUILDER => null,
            CKP::PERSISTER     => null,
            CKP::RETRIEVER     => null,
            CKP::CONFIG        => [
                CKP::PDO           => PgSqlPdo::class,
                CKP::DRIVER        => CKP::PGSQL,
                CKP::HOST          => '127.0.0.1',
                CKP::PORT          => '3306',
                CKP::DB            => CKP::VALHALLA,
                CKP::USER          => CKP::VALHALLA,
                CKP::PASSWORD      => '',
                CKP::CHARSET       => 'utf8',
                CKP::SCHEMA        => 'public',
                CKP::SSL_MODE      => 'prefer',
                CKP::SSL_CERT      => null,
                CKP::SSL_KEY       => null,
                CKP::SSL_ROOT_CERT => null,
                CKP::OPTIONS       => null,
            ],
        ],
    ];
    public const MIGRATIONS    = [];

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::DEFAULT       => self::DEFAULT,
        CKP::ADAPTER       => self::ADAPTER,
        CKP::DRIVER        => self::DRIVER,
        CKP::QUERY         => self::QUERY,
        CKP::QUERY_BUILDER => self::QUERY_BUILDER,
        CKP::PERSISTER     => self::PERSISTER,
        CKP::RETRIEVER     => self::RETRIEVER,
        CKP::REPOSITORY    => self::REPOSITORY,
        CKP::CONNECTIONS   => self::CONNECTIONS,
        CKP::MIGRATIONS    => self::MIGRATIONS,
    ];
}
