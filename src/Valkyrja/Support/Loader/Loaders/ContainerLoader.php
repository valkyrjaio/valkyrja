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

namespace Valkyrja\Support\Loader\Loaders;

use Valkyrja\Container\Container;
use Valkyrja\Support\Loader\Loader;
use Valkyrja\Support\Manager\Adapter;
use Valkyrja\Support\Manager\Driver;
use Valkyrja\Support\Type\Cls;

/**
 * Class ContainerLoader.
 *
 * @author Melech Mizrachi
 */
class ContainerLoader implements Loader
{
    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

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
     * ContainerLoader constructor.
     *
     * @param Container $container The container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function createDriver(string $name, string $adapter, array $config): Driver
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            $this->getDriverDefaultClass($name),
            [
                $this->createAdapter($adapter, $config),
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function createAdapter(string $name, array $config): Adapter
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            $this->getAdapterDefaultClass($name),
            [
                $config,
            ]
        );
    }

    /**
     * Get the default driver class.
     *
     * @param string $name The driver
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
     * @param string $name The adapter
     *
     * @return string
     */
    protected function getAdapterDefaultClass(string $name): string
    {
        return static::$defaultAdapterClass;
    }
}
