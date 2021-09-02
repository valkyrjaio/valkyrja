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

namespace Valkyrja\ORM\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\ORM\Adapters\PDOAdapter;
use Valkyrja\ORM\Drivers\Driver;
use Valkyrja\ORM\Drivers\PDO\Driver as PDODriver;
use Valkyrja\ORM\Drivers\PDO\MySqlDriver;
use Valkyrja\ORM\Drivers\PDO\PgSqlDriver;
use Valkyrja\ORM\Repositories\Repository;
use Valkyrja\ORM\Retrievers\CacheRetriever;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT     = CKP::MYSQL;
    public const ADAPTER     = CKP::PDO;
    public const ADAPTERS    = [
        CKP::PDO       => [
            CKP::NAME          => PDOAdapter::class,
            CKP::QUERY         => null,
            CKP::QUERY_BUILDER => null,
            CKP::PERSISTER     => null,
            CKP::RETRIEVER     => null,
        ],
        CKP::PDO_CACHE => [
            CKP::NAME          => PDOAdapter::class,
            CKP::QUERY         => null,
            CKP::QUERY_BUILDER => null,
            CKP::PERSISTER     => null,
            CKP::RETRIEVER     => CacheRetriever::class,
        ],
    ];
    public const DRIVERS     = [
        CKP::DEFAULT          => Driver::class,
        CKP::PDO_DRIVER       => PDODriver::class,
        CKP::PDO_MYSQL_DRIVER => MySqlDriver::class,
        CKP::PDO_PGSQL_DRIVER => PgSqlDriver::class,
    ];
    public const REPOSITORY  = Repository::class;
    public const CONNECTIONS = [
        CKP::MYSQL => [
            CKP::ADAPTER       => CKP::PDO,
            CKP::DRIVER        => CKP::PDO_MYSQL_DRIVER,
            CKP::QUERY         => null,
            CKP::QUERY_BUILDER => null,
            CKP::PERSISTER     => null,
            CKP::RETRIEVER     => null,
            CKP::HOST          => '127.0.0.1',
            CKP::PORT          => '3306',
            CKP::DB            => CKP::VALHALLA,
            CKP::USER          => CKP::VALHALLA,
            CKP::PASSWORD      => '',
            CKP::CHARSET       => 'utf8mb4',
            CKP::STRICT        => true,
            CKP::ENGINE        => null,
            CKP::OPTIONS       => null,
        ],
        CKP::PGSQL => [
            CKP::ADAPTER       => CKP::PDO,
            CKP::DRIVER        => CKP::PDO_PGSQL_DRIVER,
            CKP::QUERY         => null,
            CKP::QUERY_BUILDER => null,
            CKP::PERSISTER     => null,
            CKP::RETRIEVER     => null,
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
    ];
    public const MIGRATIONS  = [];

    public static array $defaults = [
        CKP::DEFAULT     => self::DEFAULT,
        CKP::ADAPTER     => self::ADAPTER,
        CKP::ADAPTERS    => self::ADAPTERS,
        CKP::DRIVERS     => self::DRIVERS,
        CKP::REPOSITORY  => self::REPOSITORY,
        CKP::CONNECTIONS => self::CONNECTIONS,
        CKP::MIGRATIONS  => self::MIGRATIONS,
    ];
}
