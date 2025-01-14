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
use Valkyrja\Type\BuiltIn\Support\Cls;

/**
 * Class ContainerFactory.
 *
 * @author Melech Mizrachi
 *
 * @template Adapter
 * @template Driver
 *
 * @implements Factory<Adapter, Driver>
 */
class ContainerFactory implements Factory
{
    /**
     * The default driver class.
     *
     * @var string
     */
    protected static string $defaultDriverClass;

    /**
     * The default adapter class.
     *
     * @var string
     */
    protected static string $defaultAdapterClass;

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
        /** @var class-string<Driver> $defaultDriverClass */
        $defaultDriverClass = $this->getDriverDefaultClass($name);

        return Cls::getDefaultableService(
            $this->container,
            $name,
            $defaultDriverClass,
            [
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
        /** @var class-string<Adapter> $defaultAdapterClass */
        $defaultAdapterClass = $this->getAdapterDefaultClass($name);

        return Cls::getDefaultableService(
            $this->container,
            $name,
            $defaultAdapterClass,
            [
                $config,
            ]
        );
    }

    /**
     * Get the default driver class.
     *
     * @param class-string<Driver> $name The driver
     *
     * @return string
     */
    protected function getDriverDefaultClass(string $name): string
    {
        return static::$defaultDriverClass;
    }

    /**
     * Get the default adapter class.
     *
     * @param class-string<Adapter> $name The adapter
     *
     * @return string
     */
    protected function getAdapterDefaultClass(string $name): string
    {
        return static::$defaultAdapterClass;
    }
}
