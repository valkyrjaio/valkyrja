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

namespace Valkyrja\ORM\Adapters;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
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
     * The connection to use.
     *
     * @var string
     */
    protected string $defaultConnection;

    /**
     * PDOAdapter constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config            = $config;
        $this->defaultConnection = $config[CKP::DEFAULT];
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
     * Get a connection.
     *
     * @param string|null $connection The connection to use
     *
     * @return Connection
     */
    public function getConnection(string $connection = null): Connection
    {
        $connection ??= $this->defaultConnection;

        return self::$connections[$connection]
            ?? (self::$connections[$connection] = new PDOConnection($connection, $this->config));
    }
}
