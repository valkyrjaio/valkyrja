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

namespace Valkyrja\Client\Factories;

use Valkyrja\Client\Adapter;
use Valkyrja\Client\Driver;
use Valkyrja\Client\Factory as Contract;
use Valkyrja\Client\GuzzleAdapter;
use Valkyrja\Client\LogAdapter;
use Valkyrja\Manager\Factories\ContainerFactory as Factory;
use Valkyrja\Type\Cls;

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
        return parent::createDriver($name, $adapter, $config);
    }

    /**
     * @inheritDoc
     */
    public function createAdapter(string $name, array $config): Adapter
    {
        return parent::createAdapter($name, $config);
    }

    /**
     * @inheritDoc
     */
    protected function getAdapterDefaultClass(string $name): string
    {
        $defaultClass = parent::getAdapterDefaultClass($name);

        if (Cls::inherits($name, GuzzleAdapter::class)) {
            $defaultClass = GuzzleAdapter::class;
        } elseif (Cls::inherits($name, LogAdapter::class)) {
            $defaultClass = LogAdapter::class;
        }

        return $defaultClass;
    }
}
