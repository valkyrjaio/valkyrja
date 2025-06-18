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

use Valkyrja\Orm\Constant\ConfigName;
use Valkyrja\Orm\Constant\EnvName;
use Valkyrja\Orm\Pdo\MysqlPdo;

/**
 * Class MysqlConnection.
 *
 * @author Melech Mizrachi
 */
class MysqlConnection extends PdoConnection
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS       => EnvName::MYSQL_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS        => EnvName::MYSQL_DRIVER_CLASS,
        ConfigName::REPOSITORY_CLASS    => EnvName::MYSQL_REPOSITORY_CLASS,
        ConfigName::QUERY_CLASS         => EnvName::MYSQL_QUERY_CLASS,
        ConfigName::QUERY_BUILDER_CLASS => EnvName::MYSQL_QUERY_BUILDER_CLASS,
        ConfigName::PERSISTER_CLASS     => EnvName::MYSQL_PERSISTER_CLASS,
        ConfigName::RETRIEVER_CLASS     => EnvName::MYSQL_RETRIEVER_CLASS,
        ConfigName::PDO_CLASS           => EnvName::MYSQL_PDO_CLASS,
        ConfigName::PDO_DRIVER          => EnvName::MYSQL_PDO_DRIVER,
        ConfigName::HOST                => EnvName::MYSQL_HOST,
        ConfigName::PORT                => EnvName::MYSQL_PORT,
        ConfigName::DB                  => EnvName::MYSQL_DB,
        ConfigName::USER                => EnvName::MYSQL_USER,
        ConfigName::PASSWORD            => EnvName::MYSQL_PASSWORD,
        ConfigName::CHARSET             => EnvName::MYSQL_CHARSET,
        ConfigName::OPTIONS             => EnvName::MYSQL_OPTIONS,
        ConfigName::STRICT              => EnvName::MYSQL_STRICT,
        ConfigName::ENGINE              => EnvName::MYSQL_ENGINE,
    ];

    public function __construct(
        public bool $strict = true,
        public string|null $engine = null
    ) {
        parent::__construct(
            pdoClass: MysqlPdo::class,
            pdoDriver: 'mysql',
            charset: 'utf8mb4'
        );
    }
}
