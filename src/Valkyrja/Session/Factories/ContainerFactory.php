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

namespace Valkyrja\Session\Factories;

use Valkyrja\Manager\Factories\ContainerFactory as Factory;
use Valkyrja\Session\Adapter;
use Valkyrja\Session\CacheAdapter;
use Valkyrja\Session\Driver;
use Valkyrja\Session\Factory as Contract;
use Valkyrja\Session\LogAdapter;

/**
 * Class ContainerFactory.
 *
 * @author Melech Mizrachi
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

        if (is_a($name, CacheAdapter::class, true)) {
            $defaultClass = CacheAdapter::class;
        } elseif (is_a($name, LogAdapter::class, true)) {
            $defaultClass = LogAdapter::class;
        }

        return $defaultClass;
    }
}
