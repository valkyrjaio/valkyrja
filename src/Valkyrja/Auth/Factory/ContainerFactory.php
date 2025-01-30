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

namespace Valkyrja\Auth\Factory;

use Valkyrja\Auth\Adapter\Contract\Adapter;
use Valkyrja\Auth\Config;
use Valkyrja\Auth\Factory\Contract\Factory as Contract;
use Valkyrja\Auth\Gate\Contract\Gate;
use Valkyrja\Auth\Policy\Contract\Policy;
use Valkyrja\Auth\Repository\Contract\Repository;
use Valkyrja\Container\Contract\Container;

/**
 * Class ContainerFactory.
 *
 * @author Melech Mizrachi
 */
class ContainerFactory implements Contract
{
    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * ContainerFactory constructor.
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
    public function createAdapter(string $name, Config|array $config): Adapter
    {
        return $this->container->get(
            $name,
            [
                $this->container,
                $config,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function createRepository(Adapter $adapter, string $name, string $user, Config|array $config): Repository
    {
        return $this->container->get(
            $name,
            [
                $this->container,
                $adapter,
                $user,
                $config,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function createGate(Repository $repository, string $name): Gate
    {
        return $this->container->get(
            $name,
            [
                $this->container,
                $repository,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function createPolicy(Repository $repository, string $name): Policy
    {
        return $this->container->get(
            $name,
            [
                $this->container,
                $repository,
            ]
        );
    }
}
