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

namespace Valkyrja\Crypt;

use Valkyrja\Support\Loader\Loader as Contract;

/**
 * Interface Loader.
 *
 * @author Melech Mizrachi
 */
interface Loader extends Contract
{
    /**
     * @inheritDoc
     *
     * @return Driver
     */
    public function createDriver(string $name, string $adapter, array $config): Driver;

    /**
     * @inheritDoc
     *
     * @return Adapter
     */
    public function createAdapter(string $name, array $config): Adapter;
}
