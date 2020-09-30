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

namespace Valkyrja\ORM\Drivers\PDO;

use PDO;
use Valkyrja\Container\Container;

/**
 * Class PgSqlDriver.
 *
 * @author Melech Mizrachi
 */
class PgSqlDriver extends Driver
{
    /**
     * The default options.
     *
     * @var array
     */
    protected static array $defaultOptions = [
        PDO::ATTR_PERSISTENT        => true,
        PDO::ATTR_CASE              => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
    ];

    /**
     * Driver constructor.
     *
     * @param Container $container The container
     * @param string    $adapter   The adapter
     * @param array     $config    The config
     */
    public function __construct(Container $container, string $adapter, array $config)
    {
        $schema  = $config['schema'] ?? null;
        $charset = $config['charset'] ?? 'utf8';

        $dsn = $this->getDsnPart($config, 'sslmode')
            . ";options='--client_encoding={$charset}'";

        parent::__construct($container, $adapter, $config, 'pgsql', $dsn);

        if ($schema) {
            $this->adapter->prepare("set search_path to {$schema}")->execute();
        }
    }
}
