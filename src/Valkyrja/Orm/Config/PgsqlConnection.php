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
use Valkyrja\Orm\Constant\EnvName;
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
        ConfigName::ADAPTER_CLASS       => EnvName::PGSQL_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS        => EnvName::PGSQL_DRIVER_CLASS,
        ConfigName::REPOSITORY_CLASS    => EnvName::PGSQL_REPOSITORY_CLASS,
        ConfigName::QUERY_CLASS         => EnvName::PGSQL_QUERY_CLASS,
        ConfigName::QUERY_BUILDER_CLASS => EnvName::PGSQL_QUERY_BUILDER_CLASS,
        ConfigName::PERSISTER_CLASS     => EnvName::PGSQL_PERSISTER_CLASS,
        ConfigName::RETRIEVER_CLASS     => EnvName::PGSQL_RETRIEVER_CLASS,
        ConfigName::PDO_CLASS           => EnvName::PGSQL_PDO_CLASS,
        ConfigName::PDO_DRIVER          => EnvName::PGSQL_PDO_DRIVER,
        ConfigName::HOST                => EnvName::PGSQL_HOST,
        ConfigName::PORT                => EnvName::PGSQL_PORT,
        ConfigName::DB                  => EnvName::PGSQL_DB,
        ConfigName::USER                => EnvName::PGSQL_USER,
        ConfigName::PASSWORD            => EnvName::PGSQL_PASSWORD,
        ConfigName::CHARSET             => EnvName::PGSQL_CHARSET,
        ConfigName::OPTIONS             => EnvName::PGSQL_OPTIONS,
        ConfigName::SCHEME              => EnvName::PGSQL_SCHEMA,
        ConfigName::SSL_MODE            => EnvName::PGSQL_SSL_MODE,
        ConfigName::SSL_CERT            => EnvName::PGSQL_SSL_CERT,
        ConfigName::SSL_KEY             => EnvName::PGSQL_KEY,
        ConfigName::SSL_ROOT_KEY        => EnvName::PGSQL_ROOT_KEY,
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
