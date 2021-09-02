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

namespace Valkyrja\Auth\Providers;

use Valkyrja\Auth\Adapter;
use Valkyrja\Auth\Adapters\ORMAdapter;
use Valkyrja\Auth\Auth;
use Valkyrja\Auth\Gate;
use Valkyrja\Auth\Policy;
use Valkyrja\Auth\Repository;
use Valkyrja\Auth\TokenizedRepository;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Http\Request;
use Valkyrja\ORM\ORM;
use Valkyrja\Session\Session;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Auth::class                => 'publishAuth',
            Gate::class                => 'publishGate',
            Repository::class          => 'publishRepository',
            TokenizedRepository::class => 'publishTokenizedRepository',
            Adapter::class             => 'publishAdapter',
            ORMAdapter::class          => 'publishOrmAdapter',
            Policy::class              => 'publishPolicy',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Auth::class,
            Gate::class,
            Repository::class,
            TokenizedRepository::class,
            Adapter::class,
            ORMAdapter::class,
            Policy::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function publish(Container $container): void
    {
    }

    /**
     * Publish the auth service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAuth(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Auth::class,
            new \Valkyrja\Auth\Managers\Auth(
                $container,
                $container->getSingleton(Request::class),
                $config['auth']
            )
        );
    }

    /**
     * Publish an adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAdapter(Container $container): void
    {
        $container->setClosure(
            Adapter::class,
            static function (string $name, array $config): Adapter {
                return new $name(
                    $config,
                );
            }
        );
    }

    /**
     * Publish the orm adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishOrmAdapter(Container $container): void
    {
        $container->setClosure(
            ORMAdapter::class,
            static function (array $config) use ($container): ORMAdapter {
                return new ORMAdapter(
                    $container->getSingleton(ORM::class),
                );
            }
        );
    }

    /**
     * Publish a gate service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishGate(Container $container): void
    {
        $container->setClosure(
            Gate::class,
            static function (string $name, Repository $repository, array $config) use ($container): Gate {
                return new $name(
                    $container,
                    $repository,
                    $config,
                );
            }
        );
    }

    /**
     * Publish a policy service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPolicy(Container $container): void
    {
        $container->setClosure(
            Policy::class,
            static function (string $name, Repository $repository): Policy {
                return new $name(
                    $repository,
                );
            }
        );
    }

    /**
     * Publish a repository service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRepository(Container $container): void
    {
        $container->setClosure(
            Repository::class,
            static function (string $name, Adapter $adapter, string $user, array $config) use ($container): Repository {
                return new $name(
                    $adapter,
                    $container->getSingleton(Session::class),
                    $config,
                    $user
                );
            }
        );
    }

    /**
     * Publish the tokenized repository service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishTokenizedRepository(Container $container): void
    {
        $container->setClosure(
            TokenizedRepository::class,
            static function (string $name, Adapter $adapter, string $user, array $config) use ($container): TokenizedRepository {
                return new $name(
                    $adapter,
                    $container->getSingleton(Crypt::class),
                    $container->getSingleton(Session::class),
                    $config,
                    $user
                );
            }
        );
    }
}
