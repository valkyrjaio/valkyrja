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

namespace Valkyrja\Orm\Config;

use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Orm\Config as Model;
use Valkyrja\Orm\Constant\ConfigValue;
use Valkyrja\Orm\Driver\PgSqlDriver;
use Valkyrja\Orm\Pdo\MySqlPdo;
use Valkyrja\Orm\Pdo\PgSqlPdo;

use function Valkyrja\env;

/**
 * Class ORM.
 */
class Orm extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(?array $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->connections = [
            CKP::MYSQL => [
                CKP::ADAPTER       => env(EnvKey::ORM_MYSQL_ADAPTER),
                CKP::DRIVER        => env(EnvKey::ORM_MYSQL_DRIVER),
                CKP::REPOSITORY    => env(EnvKey::ORM_MYSQL_REPOSITORY),
                CKP::QUERY         => env(EnvKey::ORM_MYSQL_QUERY),
                CKP::QUERY_BUILDER => env(EnvKey::ORM_MYSQL_QUERY_BUILDER),
                CKP::PERSISTER     => env(EnvKey::ORM_MYSQL_PERSISTER),
                CKP::RETRIEVER     => env(EnvKey::ORM_MYSQL_RETRIEVER),
                CKP::CONFIG        => [
                    CKP::PDO      => env(EnvKey::ORM_MYSQL_PDO, MySqlPdo::class),
                    CKP::DRIVER   => CKP::MYSQL,
                    CKP::HOST     => env(EnvKey::ORM_MYSQL_HOST, '127.0.0.1'),
                    CKP::PORT     => env(EnvKey::ORM_MYSQL_PORT, '3306'),
                    CKP::DB       => env(EnvKey::ORM_MYSQL_DB, CKP::VALHALLA),
                    CKP::USER     => env(EnvKey::ORM_MYSQL_USER, CKP::VALHALLA),
                    CKP::PASSWORD => env(EnvKey::ORM_MYSQL_PASSWORD, ''),
                    CKP::CHARSET  => env(EnvKey::ORM_MYSQL_CHARSET, 'utf8mb4'),
                    CKP::STRICT   => env(EnvKey::ORM_MYSQL_STRICT, true),
                    CKP::ENGINE   => env(EnvKey::ORM_MYSQL_ENGINE),
                    CKP::OPTIONS  => env(EnvKey::ORM_MYSQL_OPTIONS),
                ],
            ],
            CKP::PGSQL => [
                CKP::ADAPTER       => env(EnvKey::ORM_PGSQL_ADAPTER),
                CKP::DRIVER        => env(EnvKey::ORM_PGSQL_DRIVER, PgSqlDriver::class),
                CKP::REPOSITORY    => env(EnvKey::ORM_PGSQL_REPOSITORY),
                CKP::QUERY         => env(EnvKey::ORM_PGSQL_QUERY),
                CKP::QUERY_BUILDER => env(EnvKey::ORM_PGSQL_QUERY_BUILDER),
                CKP::PERSISTER     => env(EnvKey::ORM_PGSQL_PERSISTER),
                CKP::RETRIEVER     => env(EnvKey::ORM_PGSQL_RETRIEVER),
                CKP::CONFIG        => [
                    CKP::PDO           => env(EnvKey::ORM_PGSQL_PDO, PgSqlPdo::class),
                    CKP::DRIVER        => CKP::PGSQL,
                    CKP::HOST          => env(EnvKey::ORM_PGSQL_HOST, '127.0.0.1'),
                    CKP::PORT          => env(EnvKey::ORM_PGSQL_PORT, '5432'),
                    CKP::DB            => env(EnvKey::ORM_PGSQL_DB, CKP::VALHALLA),
                    CKP::USER          => env(EnvKey::ORM_PGSQL_USER, CKP::VALHALLA),
                    CKP::PASSWORD      => env(EnvKey::ORM_PGSQL_PASSWORD, ''),
                    CKP::CHARSET       => env(EnvKey::ORM_PGSQL_CHARSET, 'utf8'),
                    CKP::SCHEMA        => env(EnvKey::ORM_PGSQL_SCHEMA, 'public'),
                    CKP::SSL_MODE      => env(EnvKey::ORM_PGSQL_SSL_MODE, 'prefer'),
                    CKP::SSL_CERT      => env(EnvKey::ORM_PGSQL_SSL_CERT),
                    CKP::SSL_KEY       => env(EnvKey::ORM_PGSQL_SSL_KEY),
                    CKP::SSL_ROOT_CERT => env(EnvKey::ORM_PGSQL_SSL_ROOT_CERT),
                    CKP::OPTIONS       => env(EnvKey::ORM_PGSQL_OPTIONS),
                ],
            ],
        ];
    }
}
