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

namespace Valkyrja\Cache\Factory;

use Valkyrja\Cache\Adapter\Contract\Adapter;
use Valkyrja\Cache\Adapter\Contract\LogAdapter;
use Valkyrja\Cache\Adapter\Contract\RedisAdapter;
use Valkyrja\Cache\Driver\Contract\Driver;
use Valkyrja\Cache\Factory\Contract\Factory as Contract;
use Valkyrja\Manager\Factory\ContainerFactory as Factory;

/**
 * Class ContainerFactory.
 *
 * @author Melech Mizrachi
 *
 * @extends Factory<Adapter, Driver>
 */
class ContainerFactory extends Factory implements Contract
{
    /**
     * @inheritDoc
     */
    protected static string $defaultDriverClass = Driver::class;

    /**
     * @inheritDoc
     */
    protected static string $defaultAdapterClass = Adapter::class;

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

    /**
     * @inheritDoc
     */
    protected function getAdapterDefaultClass(string $name): string
    {
        $defaultClass = parent::getAdapterDefaultClass($name);

        if (is_a($name, RedisAdapter::class, true)) {
            $defaultClass = RedisAdapter::class;
        } elseif (is_a($name, LogAdapter::class, true)) {
            $defaultClass = LogAdapter::class;
        }

        return $defaultClass;
    }
}
