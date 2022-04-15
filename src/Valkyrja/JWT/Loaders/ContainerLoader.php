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

namespace Valkyrja\JWT\Loaders;

use Valkyrja\Container\Container;
use Valkyrja\JWT\Adapter;
use Valkyrja\JWT\Driver;
use Valkyrja\JWT\Loader as Contract;
use Valkyrja\Support\Type\Cls;

/**
 * Class ContainerLoader.
 *
 * @author Melech Mizrachi
 */
class ContainerLoader implements Contract
{
    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

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
            Driver::class,
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
            Adapter::class,
            [
                $config,
            ]
        );
    }
}
