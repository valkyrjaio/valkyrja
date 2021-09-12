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

namespace Valkyrja\ORM;

/**
 * Interface DriverFactory.
 *
 * @author Melech Mizrachi
 */
interface DriverFactory
{
    /**
     * Create a driver.
     *
     * @template T
     *
     * @param Adapter         $adapter The adapter
     * @param class-string<T> $name    The driver class name
     * @param array           $config  The config
     *
     * @return T
     */
    public function createDriver(Adapter $adapter, string $name, array $config): Driver;
}
