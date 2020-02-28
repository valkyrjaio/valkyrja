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

namespace Valkyrja\ORM;

use Valkyrja\Config\Configs\ORMConfig;

/**
 * Interface Adapter.
 *
 * @author Melech Mizrachi
 */
interface Adapter
{
    /**
     * Make a new adapter.
     *
     * @param ORMConfig $config
     *
     * @return static
     */
    public static function make(ORMConfig $config): self;

    /**
     * Create a new connection.
     *
     * @param string|null $connection The connection to use
     *
     * @return Connection
     */
    public function createConnection(string $connection = null): Connection;
}
