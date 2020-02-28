<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM\Adapters;

use Valkyrja\Config\Configs\ORMConfig;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Connection;
use Valkyrja\ORM\Connections\PDOConnection;

/**
 * Class PDOAdapter.
 *
 * @author Melech Mizrachi
 */
class PDOAdapter implements Adapter
{
    /**
     * Connections.
     *
     * @var Connection[]
     */
    protected static array $connections = [];

    /**
     * The config.
     *
     * @var ORMConfig
     */
    protected ORMConfig $config;

    /**
     * The connection to use.
     *
     * @var string
     */
    protected string $defaultConnection;

    /**
     * PDOAdapter constructor.
     *
     * @param ORMConfig $config
     */
    public function __construct(ORMConfig $config)
    {
        $this->config            = $config;
        $this->defaultConnection = $this->config[CKP::DEFAULT];
    }

    /**
     * Make a new adapter.
     *
     * @param ORMConfig $config
     *
     * @return static
     */
    public static function make(ORMConfig $config): self
    {
        return new static($config);
    }

    /**
     * Create a new connection.
     *
     * @param string|null $connection The connection to use
     *
     * @return Connection
     */
    public function createConnection(string $connection = null): Connection
    {
        $connection ??= $this->defaultConnection;

        return self::$connections[$connection]
            ?? (self::$connections[$connection] = new PDOConnection($this->config->connections->{$connection}));
    }
}
