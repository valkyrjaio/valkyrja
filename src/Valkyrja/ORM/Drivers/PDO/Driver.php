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

use function Valkyrja\dd;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 */
class Driver extends \Valkyrja\ORM\Drivers\Driver
{
    /**
     * The default options.
     *
     * @var array
     */
    protected static array $defaultOptions = [
        PDO::ATTR_CASE              => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES  => false,
    ];

    /**
     * Driver constructor.
     *
     * @param Container   $container The container
     * @param string      $adapter   The adapter
     * @param array       $config    The config
     * @param string|null $driver    [optional] The driver
     * @param string|null $dsn       [optional] The added dsn
     */
    public function __construct(
        Container $container,
        string $adapter,
        array $config,
        string $driver = null,
        string $dsn = null
    ) {
        $config['dsn'] = ($driver ?? 'mysql')
            . ":dbname={$config['db']}"
            . $this->getDsnPart($config, 'host')
            . $this->getDsnPart($config, 'port')
            . $this->getDsnPart($config, 'user')
            . $this->getDsnPart($config, 'password')
            . ($dsn ?? '');

        parent::__construct($container, $adapter, $config);
    }

    /**
     * Get dsn part.
     *
     * @param array  $config  The config
     * @param string $name    The dsn part name
     * @param mixed  $default [optional] The default value
     *
     * @return string
     */
    protected function getDsnPart(array $config, string $name, $default = null): string
    {
        $value = $config[$name] ?? $default;

        return $value ? ";{$name}={$value}" : '';
    }
}
