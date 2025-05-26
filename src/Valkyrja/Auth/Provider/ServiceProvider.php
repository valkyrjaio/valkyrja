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

namespace Valkyrja\Auth\Provider;

use Valkyrja\Auth\Adapter\Contract\Adapter;
use Valkyrja\Auth\Adapter\NullAdapter;
use Valkyrja\Auth\Adapter\ORMAdapter;
use Valkyrja\Auth\Config;
use Valkyrja\Auth\Contract\Auth;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Factory\ContainerFactory;
use Valkyrja\Auth\Factory\Contract\Factory;
use Valkyrja\Auth\Gate\Contract\Gate;
use Valkyrja\Auth\Policy\Contract\EntityPolicy;
use Valkyrja\Auth\Policy\Contract\Policy;
use Valkyrja\Auth\Policy\EntityRoutePolicy;
use Valkyrja\Auth\Repository\CryptTokenizedRepository;
use Valkyrja\Auth\Repository\JwtCryptRepository;
use Valkyrja\Auth\Repository\JwtRepository;
use Valkyrja\Auth\Repository\Repository;
use Valkyrja\Config\Config\ValkyrjaDataConfig;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Jwt\Contract\Jwt;
use Valkyrja\Orm\Contract\Orm;
use Valkyrja\Session\Contract\Session;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Auth::class                     => [self::class, 'publishAuth'],
            Factory::class                  => [self::class, 'publishFactory'],
            Gate::class                     => [self::class, 'publishGate'],
            Repository::class               => [self::class, 'publishRepository'],
            CryptTokenizedRepository::class => [self::class, 'publishCryptTokenizedRepository'],
            JwtRepository::class            => [self::class, 'publishJwtRepository'],
            JwtCryptRepository::class       => [self::class, 'publishJwtCryptRepository'],
            NullAdapter::class              => [self::class, 'publishNullAdapter'],
            ORMAdapter::class               => [self::class, 'publishOrmAdapter'],
            Policy::class                   => [self::class, 'publishPolicy'],
            EntityPolicy::class             => [self::class, 'publishEntityPolicy'],
            EntityRoutePolicy::class        => [self::class, 'publishEntityRoutePolicy'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Auth::class,
            Factory::class,
            Gate::class,
            Repository::class,
            CryptTokenizedRepository::class,
            JwtRepository::class,
            JwtCryptRepository::class,
            NullAdapter::class,
            ORMAdapter::class,
            Policy::class,
            EntityPolicy::class,
            EntityRoutePolicy::class,
        ];
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
        $config = $container->getSingleton(ValkyrjaDataConfig::class);

        $container->setSingleton(
            Auth::class,
            new \Valkyrja\Auth\Auth(
                $container->getSingleton(Factory::class),
                $container->getSingleton(ServerRequest::class),
                $config->auth
            )
        );
    }

    /**
     * Publish the factory service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFactory(Container $container): void
    {
        $container->setSingleton(
            Factory::class,
            new ContainerFactory(
                $container,
            )
        );
    }

    /**
     * Publish the null adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNullAdapter(Container $container): void
    {
        $container->setCallable(
            NullAdapter::class,
            [self::class, 'createNullAdapter']
        );
    }

    /**
     * Create a null adapter.
     */
    public static function createNullAdapter(Container $container, Config $config): NullAdapter
    {
        return new NullAdapter(
            $config,
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
        $container->setCallable(
            ORMAdapter::class,
            [self::class, 'createOrmAdapter']
        );
    }

    /**
     * Create an ORM adapter.
     */
    public static function createOrmAdapter(Container $container, Config $config): ORMAdapter
    {
        $orm = $container->getSingleton(Orm::class);

        return new ORMAdapter(
            $orm,
            $config
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
        $container->setCallable(
            Gate::class,
            [self::class, 'createGate']
        );
    }

    /**
     * @param Container          $container
     * @param class-string<Gate> $name
     * @param Repository         $repository
     *
     * @return Gate
     */
    public static function createGate(Container $container, string $name, Repository $repository): Gate
    {
        return new $name(
            $container->getSingleton(Auth::class),
            $repository,
        );
    }

    /**
     * Publish the policy service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPolicy(Container $container): void
    {
        $container->setCallable(
            Policy::class,
            [self::class, 'createPolicy']
        );
    }

    /**
     * Create a policy.
     *
     * @param Container            $container
     * @param class-string<Policy> $name
     * @param Repository           $repository
     *
     * @return Policy
     */
    public static function createPolicy(Container $container, string $name, Repository $repository): Policy
    {
        return new $name(
            $repository,
        );
    }

    /**
     * Publish the entity policy service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishEntityPolicy(Container $container): void
    {
        $container->setCallable(
            EntityPolicy::class,
            [self::class, 'createEntityPolicy']
        );
    }

    /**
     * Create an entity policy.
     *
     * @param Container                  $container
     * @param class-string<EntityPolicy> $name
     * @param Repository                 $repository
     *
     * @return EntityPolicy
     */
    public static function createEntityPolicy(Container $container, string $name, Repository $repository): EntityPolicy
    {
        return new $name(
            $repository,
            $container->getSingleton($name::getEntityClassName()),
        );
    }

    /**
     * Publish the entity route policy service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishEntityRoutePolicy(Container $container): void
    {
        $container->setCallable(
            EntityRoutePolicy::class,
            [self::class, 'createEntityRoutePolicy']
        );
    }

    /**
     * Create an entity route policy.
     *
     * @param Container                       $container
     * @param class-string<EntityRoutePolicy> $name
     * @param Repository                      $repository
     *
     * @return EntityRoutePolicy
     */
    public static function createEntityRoutePolicy(Container $container, string $name, Repository $repository): EntityRoutePolicy
    {
        return new $name(
            $repository,
            $container->getSingleton($name::getEntityClassName() . ((string) $name::getEntityParamNumber())),
        );
    }

    /**
     * Publish the repository service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRepository(Container $container): void
    {
        $container->setCallable(
            Repository::class,
            [self::class, 'createRepository']
        );
    }

    /**
     * Create a repository.
     *
     * @param class-string<User> $user The user entity class
     */
    public static function createRepository(Container $container, Adapter $adapter, string $user, Config $config): Repository
    {
        return new Repository(
            $adapter,
            $container->getSingleton(Session::class),
            $config,
            $user
        );
    }

    /**
     * Publish the crypt tokenized repository service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCryptTokenizedRepository(Container $container): void
    {
        $container->setCallable(
            CryptTokenizedRepository::class,
            [self::class, 'createCryptTokenizedRepository']
        );
    }

    /**
     * Create a crypt tokenized repository.
     *
     * @param class-string<User> $user The user entity class
     */
    public static function createCryptTokenizedRepository(Container $container, Adapter $adapter, string $user, Config $config): CryptTokenizedRepository
    {
        return new CryptTokenizedRepository(
            $adapter,
            $container->getSingleton(Crypt::class),
            $container->getSingleton(Session::class),
            $config,
            $user
        );
    }

    /**
     * Publish the JWT crypt tokenized repository service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishJwtCryptRepository(Container $container): void
    {
        $container->setCallable(
            JwtCryptRepository::class,
            [self::class, 'createJwtCryptRepository']
        );
    }

    /**
     * Create a JWT crypt tokenized repository.
     *
     * @param class-string<User> $user THe user entity class
     */
    public static function createJwtCryptRepository(Container $container, Adapter $adapter, string $user, Config $config): JwtCryptRepository
    {
        return new JwtCryptRepository(
            $adapter,
            $container->getSingleton(Jwt::class),
            $container->getSingleton(Crypt::class),
            $container->getSingleton(Session::class),
            $config,
            $user
        );
    }

    /**
     * Publish the JWT tokenized repository service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishJwtRepository(Container $container): void
    {
        $container->setCallable(
            JwtRepository::class,
            [self::class, 'createJwtRepository']
        );
    }

    /**
     * Create a JWT tokenized repository.
     *
     * @param class-string<User> $user The user entity class
     */
    public static function createJwtRepository(Container $container, Adapter $adapter, string $user, Config $config): JwtRepository
    {
        return new JwtRepository(
            $adapter,
            $container->getSingleton(Jwt::class),
            $container->getSingleton(Session::class),
            $config,
            $user
        );
    }
}
