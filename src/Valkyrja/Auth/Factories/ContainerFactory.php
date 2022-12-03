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

namespace Valkyrja\Auth\Factories;

use Valkyrja\Auth\Adapter;
use Valkyrja\Auth\Config\Config;
use Valkyrja\Auth\CryptTokenizedRepository;
use Valkyrja\Auth\EntityPolicy;
use Valkyrja\Auth\EntityRoutePolicy;
use Valkyrja\Auth\Factory as Contract;
use Valkyrja\Auth\Gate;
use Valkyrja\Auth\JWTCryptRepository;
use Valkyrja\Auth\JWTRepository;
use Valkyrja\Auth\ORMAdapter;
use Valkyrja\Auth\Policy;
use Valkyrja\Auth\Repository;
use Valkyrja\Container\Container;
use Valkyrja\Type\Cls;

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
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Cls::inherits($name, ORMAdapter::class) ? ORMAdapter::class : Adapter::class,
            [
                $config,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function createRepository(Adapter $adapter, string $name, string $user, Config|array $config): Repository
    {
        $defaultClass = Repository::class;

        if (Cls::inherits($name, JWTCryptRepository::class)) {
            $defaultClass = JWTCryptRepository::class;
        } elseif (Cls::inherits($name, CryptTokenizedRepository::class)) {
            $defaultClass = CryptTokenizedRepository::class;
        } elseif (Cls::inherits($name, JWTRepository::class)) {
            $defaultClass = JWTRepository::class;
        }

        return Cls::getDefaultableService(
            $this->container,
            $name,
            $defaultClass,
            [
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
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Gate::class,
            [
                $repository,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function createPolicy(Repository $repository, string $name): Policy
    {
        $defaultClass = Policy::class;

        if (Cls::inherits($name, EntityRoutePolicy::class)) {
            $defaultClass = EntityRoutePolicy::class;
        } elseif (Cls::inherits($name, EntityPolicy::class)) {
            $defaultClass = EntityPolicy::class;
        }

        return Cls::getDefaultableService(
            $this->container,
            $name,
            $defaultClass,
            [
                $repository,
            ]
        );
    }
}
