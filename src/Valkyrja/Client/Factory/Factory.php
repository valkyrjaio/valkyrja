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

namespace Valkyrja\Client\Factory;

use Valkyrja\Client\Adapter\Contract\Adapter;
use Valkyrja\Client\Driver\Contract\Driver;
use Valkyrja\Client\Factory\Contract\Factory as Contract;
use Valkyrja\Manager\Factory\Factory as ManagerFactory;

/**
 * Class Factory.
 *
 * @author Melech Mizrachi
 */
class Factory extends ManagerFactory implements Contract
{
    /**
     * @inheritDoc
     */
    public function createDriver(string $name, string $adapter, array $config): Driver
    {
        /** @var Driver $driver */
        $driver = parent::createDriver($name, $adapter, $config);

        return $driver;
    }

    /**
     * @inheritDoc
     */
    public function createAdapter(string $name, array $config): Adapter
    {
        /** @var Adapter $adapter */
        $adapter = parent::createAdapter($name, $config);

        return $adapter;
    }
}
