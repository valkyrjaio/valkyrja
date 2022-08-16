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
use Valkyrja\Auth\CryptTokenizedRepository;
use Valkyrja\Auth\Factory as Contract;
use Valkyrja\Auth\Gate;
use Valkyrja\Auth\JWTCryptRepository;
use Valkyrja\Auth\JWTRepository;
use Valkyrja\Auth\ORMAdapter;
use Valkyrja\Auth\Repository;
use Valkyrja\Container\Container;
use Valkyrja\Support\Type\Cls;

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
    public function createAdapter(string $name, array $config): Adapter
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
    public function createRepository(Adapter $adapter, string $name, string $user, array $config): Repository
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
    public function createGate(Repository $repository, string $name, array $config): Gate
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Gate::class,
            [
                $repository,
                $config,
            ]
        );
    }
}
