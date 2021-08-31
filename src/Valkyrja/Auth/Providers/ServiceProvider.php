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
use Valkyrja\Auth\Gates\Gate;
use Valkyrja\Auth\Policies\UserPermissiblePolicy;
use Valkyrja\Auth\Repositories\Repository;
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
            Auth::class                  => 'publishAuth',
            Gate::class                  => 'publishGate',
            Repository::class            => 'publishRepository',
            ORMAdapter::class            => 'publishAdapter',
            UserPermissiblePolicy::class => 'publishPolicy',
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
            ORMAdapter::class,
            UserPermissiblePolicy::class,
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
     * Publish the default adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAdapter(Container $container): void
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
     * Publish the default adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishGate(Container $container): void
    {
        $container->setClosure(
            Gate::class,
            static function (\Valkyrja\Auth\Repository $repository, array $config) use ($container): Gate {
                return new Gate(
                    $container,
                    $repository,
                    $config,
                );
            }
        );
    }

    /**
     * Publish the default adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPolicy(Container $container): void
    {
        $container->setClosure(
            UserPermissiblePolicy::class,
            static function (\Valkyrja\Auth\Repository $repository): UserPermissiblePolicy {
                return new UserPermissiblePolicy(
                    $repository,
                );
            }
        );
    }

    /**
     * Publish the default repository service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRepository(Container $container): void
    {
        $container->setClosure(
            Repository::class,
            static function (Adapter $adapter, string $user, array $config) use ($container): Repository {
                return new Repository(
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
