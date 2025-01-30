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

use Valkyrja\Container\Contract\Container;
use Valkyrja\Manager\Adapter\Contract\Adapter;
use Valkyrja\Manager\Driver\Contract\Driver;
use Valkyrja\Manager\Factory\Contract\Factory;

/**
 * Class ContainerFactory.
 *
 * @author Melech Mizrachi
 *
 * @template Adapter of Adapter
 * @template Driver of Driver
 *
 * @implements Factory<Adapter, Driver>
 */
class ContainerFactory implements Factory
{
    /**
     * ContainerFactory constructor.
     *
     * @param Container $container The container
     */
    public function __construct(
        protected Container $container
    ) {
    }

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
        return $this->container->get(
            $name,
            [
                $this->container,
                $this->createAdapter($adapter, $config),
            ]
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
        return $this->container->get(
            $name,
            [
                $this->container,
                $config,
            ]
        );
    }
}
