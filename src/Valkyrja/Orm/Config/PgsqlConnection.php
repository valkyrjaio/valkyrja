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

use Valkyrja\Orm\Adapter\PgsqlPdoAdapter;
use Valkyrja\Orm\Constant\ConfigName;
use Valkyrja\Orm\Driver\PgsqlDriver;
use Valkyrja\Orm\Pdo\PgsqlPdo;

/**
 * Class PgsqlConnection.
 *
 * @author Melech Mizrachi
 */
class PgsqlConnection extends PdoConnection
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS       => 'ORM_PGSQL_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS        => 'ORM_PGSQL_DRIVER_CLASS',
        ConfigName::REPOSITORY_CLASS    => 'ORM_PGSQL_REPOSITORY_CLASS',
        ConfigName::QUERY_CLASS         => 'ORM_PGSQL_QUERY_CLASS',
        ConfigName::QUERY_BUILDER_CLASS => 'ORM_PGSQL_QUERY_BUILDER_CLASS',
        ConfigName::PERSISTER_CLASS     => 'ORM_PGSQL_PERSISTER_CLASS',
        ConfigName::RETRIEVER_CLASS     => 'ORM_PGSQL_RETRIEVER_CLASS',
        ConfigName::PDO_CLASS           => 'ORM_PGSQL_PDO_CLASS',
        ConfigName::PDO_DRIVER          => 'ORM_PGSQL_PDO_DRIVER',
        ConfigName::HOST                => 'ORM_PGSQL_HOST',
        ConfigName::PORT                => 'ORM_PGSQL_PORT',
        ConfigName::DB                  => 'ORM_PGSQL_DB',
        ConfigName::USER                => 'ORM_PGSQL_USER',
        ConfigName::PASSWORD            => 'ORM_PGSQL_PASSWORD',
        ConfigName::CHARSET             => 'ORM_PGSQL_CHARSET',
        ConfigName::OPTIONS             => 'ORM_PGSQL_OPTIONS',
        'schema'                        => 'ORM_PGSQL_SCHEMA',
        'sslMode'                       => 'ORM_PGSQL_SSL_MODE',
        'sslCert'                       => 'ORM_PGSQL_SSL_CERT',
        'sslKey'                        => 'ORM_PGSQL_KEY',
        'sslRootKey'                    => 'ORM_PGSQL_ROOT_KEY',
    ];

    public function __construct(
        public string $schema = 'public',
        public string $sslMode = 'prefer',
        public string|null $sslCert = null,
        public string|null $sslKey = null,
        public string|null $sslRootKey = null,
    ) {
        parent::__construct(
            pdoClass: PgsqlPdo::class,
            pdoDriver: 'pgsql'
        );

        $this->adapterClass = PgsqlPdoAdapter::class;
        $this->driverClass  = PgsqlDriver::class;
    }
}
