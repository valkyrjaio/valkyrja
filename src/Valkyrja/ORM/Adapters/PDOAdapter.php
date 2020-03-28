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

use Valkyrja\Config\Configs\ORM;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Connection;
use Valkyrja\ORM\Connections\PDOConnection;

use function Valkyrja\config;

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
     * @var ORM|array
     */
    protected $config;

    /**
     * The connection to use.
     *
     * @var string
     */
    protected string $defaultConnection;

    /**
     * PDOAdapter constructor.
     */
    public function __construct()
    {
        $this->config            = config()['orm'];
        $this->defaultConnection = $this->config[CKP::DEFAULT];
    }

    /**
     * Make a new adapter.
     *
     * @return static
     */
    public static function make(): self
    {
        return new static();
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
            ?? (self::$connections[$connection] = new PDOConnection($connection));
    }
}
