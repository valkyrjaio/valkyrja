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
        ConfigName::ADAPTER_CLASS       => 'ORM_MYSQL_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS        => 'ORM_MYSQL_DRIVER_CLASS',
        ConfigName::REPOSITORY_CLASS    => 'ORM_MYSQL_REPOSITORY_CLASS',
        ConfigName::QUERY_CLASS         => 'ORM_MYSQL_QUERY_CLASS',
        ConfigName::QUERY_BUILDER_CLASS => 'ORM_MYSQL_QUERY_BUILDER_CLASS',
        ConfigName::PERSISTER_CLASS     => 'ORM_MYSQL_PERSISTER_CLASS',
        ConfigName::RETRIEVER_CLASS     => 'ORM_MYSQL_RETRIEVER_CLASS',
        ConfigName::PDO_CLASS           => 'ORM_MYSQL_PDO_CLASS',
        ConfigName::PDO_DRIVER          => 'ORM_MYSQL_PDO_DRIVER',
        ConfigName::HOST                => 'ORM_MYSQL_HOST',
        ConfigName::PORT                => 'ORM_MYSQL_PORT',
        ConfigName::DB                  => 'ORM_MYSQL_DB',
        ConfigName::USER                => 'ORM_MYSQL_USER',
        ConfigName::PASSWORD            => 'ORM_MYSQL_PASSWORD',
        ConfigName::CHARSET             => 'ORM_MYSQL_CHARSET',
        ConfigName::OPTIONS             => 'ORM_MYSQL_OPTIONS',
        'strict'                        => 'ORM_MYSQL_STRICT',
        'engine'                        => 'ORM_MYSQL_ENGINE',
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
