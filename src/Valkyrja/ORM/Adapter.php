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
     * @return static
     */
    public static function make(): self;

    /**
     * Get a connection.
     *
     * @param string|null $connection The connection to use
     *
     * @return Connection
     */
    public function getConnection(string $connection = null): Connection;
}
