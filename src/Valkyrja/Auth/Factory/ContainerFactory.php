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
use Valkyrja\Auth\Adapter\Contract\ORMAdapter;
use Valkyrja\Auth\Config;
use Valkyrja\Auth\Factory\Contract\Factory as Contract;
use Valkyrja\Auth\Gate\Contract\Gate;
use Valkyrja\Auth\Policy\Contract\EntityPolicy;
use Valkyrja\Auth\Policy\Contract\EntityRoutePolicy;
use Valkyrja\Auth\Policy\Contract\Policy;
use Valkyrja\Auth\Repository\Contract\CryptTokenizedRepository;
use Valkyrja\Auth\Repository\Contract\JWTCryptRepository;
use Valkyrja\Auth\Repository\Contract\JWTRepository;
use Valkyrja\Auth\Repository\Contract\Repository;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Type\BuiltIn\Support\Cls;

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
            is_a($name, ORMAdapter::class, true) ? ORMAdapter::class : Adapter::class,
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

        if (is_a($name, JWTCryptRepository::class, true)) {
            $defaultClass = JWTCryptRepository::class;
        } elseif (is_a($name, CryptTokenizedRepository::class, true)) {
            $defaultClass = CryptTokenizedRepository::class;
        } elseif (is_a($name, JWTRepository::class, true)) {
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

        if (is_a($name, EntityRoutePolicy::class, true)) {
            $defaultClass = EntityRoutePolicy::class;
        } elseif (is_a($name, EntityPolicy::class, true)) {
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
