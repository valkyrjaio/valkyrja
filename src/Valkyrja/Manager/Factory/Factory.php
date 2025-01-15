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

namespace Valkyrja\Manager\Factory;

use Valkyrja\Manager\Adapter\Contract\Adapter;
use Valkyrja\Manager\Driver\Contract\Driver;
use Valkyrja\Manager\Factory\Contract\Factory as Contract;

/**
 * Class Factory.
 *
 * @author Melech Mizrachi
 *
 * @template Adapter of Adapter
 * @template Driver of Driver
 *
 * @implements Contract<Adapter, Driver>
 */
class Factory implements Contract
{
    /**
     * @inheritDoc
     *
     * @param class-string<Driver>  $name    The driver
     * @param class-string<Adapter> $adapter The adapter
     *
     * @return Driver
     */
    public function createDriver(string $name, string $adapter, array $config): Driver
    {
        return new $name(
            $this->createAdapter($adapter, $config)
        );
    }

    /**
     * @inheritDoc
     *
     * @param class-string<Adapter> $name The adapter
     *
     * @return Adapter
     */
    public function createAdapter(string $name, array $config): Adapter
    {
        return new $name($config);
    }
}
