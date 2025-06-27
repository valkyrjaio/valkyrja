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

namespace Valkyrja\Orm;

use Valkyrja\Config\Config as ParentConfig;
use Valkyrja\Orm\Config\Connections;
use Valkyrja\Orm\Config\MysqlConnection;
use Valkyrja\Orm\Config\PgsqlConnection;
use Valkyrja\Orm\Constant\ConfigName;
use Valkyrja\Orm\Constant\EnvName;
use Valkyrja\Orm\Schema\Contract\Migration;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::DEFAULT_CONNECTION => EnvName::DEFAULT_CONNECTION,
        ConfigName::CONNECTIONS        => EnvName::CONNECTIONS,
        ConfigName::MIGRATIONS         => EnvName::MIGRATIONS,
    ];

    /**
     * @param array<string, Migration> $migrations A list of migrations
     */
    public function __construct(
        public string $defaultConnection = '',
        public Connections|null $connections = null,
        public array $migrations = [],
    ) {
    }

    /**
     * @inheritDoc
     */
    public function setPropertiesFromEnv(string $env): void
    {
        if ($this->connections === null) {
            $this->connections = new Connections(
                mysql: MysqlConnection::fromEnv($env),
                pgsql: PgsqlConnection::fromEnv($env)
            );
        }

        if ($this->defaultConnection === '') {
            $this->defaultConnection = (string) array_key_first((array) $this->connections);
        }

        parent::setPropertiesFromEnv($env);
    }
}
