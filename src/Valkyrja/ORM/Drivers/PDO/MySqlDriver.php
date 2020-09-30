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

use Valkyrja\Container\Container;

/**
 * Class MySqlDriver.
 *
 * @author Melech Mizrachi
 */
class MySqlDriver extends Driver
{
    /**
     * Driver constructor.
     *
     * @param Container $container The container
     * @param string    $adapter   The adapter
     * @param array     $config    The config
     */
    public function __construct(Container $container, string $adapter, array $config)
    {
        $dsn = $this->getDsnPart($config, 'charset')
            . $this->getDsnPart($config, 'strict')
            . $this->getDsnPart($config, 'engine');

        parent::__construct($container, $adapter, $config, 'mysql', $dsn);
    }
}
