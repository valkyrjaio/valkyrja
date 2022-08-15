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

namespace Valkyrja\Support\Loader;

use Valkyrja\Support\Manager\Adapter;
use Valkyrja\Support\Manager\Driver;

/**
 * Interface Loader.
 *
 * @author Melech Mizrachi
 */
interface Loader
{
    /**
     * Get a driver by name.
     *
     * @param string $name    The driver
     * @param string $adapter The adapter
     * @param array  $config  The config
     *
     * @return Driver
     */
    public function createDriver(string $name, string $adapter, array $config): Driver;

    /**
     * Get an adapter by name.
     *
     * @param string $name   The adapter
     * @param array  $config The config
     *
     * @return Adapter
     */
    public function createAdapter(string $name, array $config): Adapter;
}
