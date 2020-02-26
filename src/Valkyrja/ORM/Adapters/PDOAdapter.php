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
     * @var array
     */
    protected array $config;

    /**
     * PDOAdapter constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Make a new adapter.
     *
     * @param array $config
     *
     * @return static
     */
    public static function make(array $config): self
    {
        return new static($config);
    }

    /**
     * Create a new connection.
     *
     * @param string $connection The connection to use
     *
     * @return Connection
     */
    public function createConnection(string $connection): Connection
    {
        return self::$connections[$connection]
            ?? (self::$connections[$connection] = new PDOConnection($this->config, $connection));
    }
}
