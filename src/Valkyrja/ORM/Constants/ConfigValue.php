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
use Valkyrja\ORM\PDOs\MySqlPDO;
use Valkyrja\ORM\PDOs\PgSqlPDO;
use Valkyrja\ORM\Persisters\Persister;
use Valkyrja\ORM\Queries\Query;
use Valkyrja\ORM\QueryBuilders\SqlQueryBuilder;
use Valkyrja\ORM\Repositories\Repository;
use Valkyrja\ORM\Retrievers\Retriever;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT       = CKP::MYSQL;
    public const ADAPTER       = PDOAdapter::class;
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
                CKP::PDO      => MySqlPDO::class,
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
            CKP::DRIVER        => null,
            CKP::REPOSITORY    => null,
            CKP::QUERY         => null,
            CKP::QUERY_BUILDER => null,
            CKP::PERSISTER     => null,
            CKP::RETRIEVER     => null,
            CKP::CONFIG        => [
                CKP::PDO           => PgSqlPDO::class,
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
