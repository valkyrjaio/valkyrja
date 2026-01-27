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

namespace Valkyrja\Tests\Unit\Auth\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Auth\Authenticator\Contract\AuthenticatorContract;
use Valkyrja\Auth\Authenticator\SessionAuthenticator;
use Valkyrja\Auth\Hasher\Contract\PasswordHasherContract;
use Valkyrja\Auth\Hasher\PhpPasswordHasher;
use Valkyrja\Auth\Provider\ServiceProvider;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Auth\Store\InMemoryStore;
use Valkyrja\Auth\Store\NullStore;
use Valkyrja\Auth\Store\OrmStore;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(AuthenticatorContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(SessionAuthenticator::class, ServiceProvider::publishers());
        self::assertArrayHasKey(StoreContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(OrmStore::class, ServiceProvider::publishers());
        self::assertArrayHasKey(InMemoryStore::class, ServiceProvider::publishers());
        self::assertArrayHasKey(NullStore::class, ServiceProvider::publishers());
        self::assertArrayHasKey(PasswordHasherContract::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(AuthenticatorContract::class, ServiceProvider::provides());
        self::assertContains(SessionAuthenticator::class, ServiceProvider::provides());
        self::assertContains(StoreContract::class, ServiceProvider::provides());
        self::assertContains(OrmStore::class, ServiceProvider::provides());
        self::assertContains(InMemoryStore::class, ServiceProvider::provides());
        self::assertContains(NullStore::class, ServiceProvider::provides());
        self::assertContains(PasswordHasherContract::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishAuthenticator(): void
    {
        $this->container->setSingleton(SessionAuthenticator::class, self::createStub(SessionAuthenticator::class));

        $callback = ServiceProvider::publishers()[AuthenticatorContract::class];
        $callback($this->container);

        self::assertInstanceOf(SessionAuthenticator::class, $this->container->getSingleton(AuthenticatorContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishSessionAuthenticator(): void
    {
        $this->container->setSingleton(SessionContract::class, self::createStub(SessionContract::class));
        $this->container->setSingleton(StoreContract::class, self::createStub(StoreContract::class));
        $this->container->setSingleton(PasswordHasherContract::class, self::createStub(PasswordHasherContract::class));

        $callback = ServiceProvider::publishers()[SessionAuthenticator::class];
        $callback($this->container);

        self::assertInstanceOf(SessionAuthenticator::class, $this->container->getSingleton(SessionAuthenticator::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishStore(): void
    {
        $this->container->setSingleton(OrmStore::class, self::createStub(OrmStore::class));

        $callback = ServiceProvider::publishers()[StoreContract::class];
        $callback($this->container);

        self::assertInstanceOf(OrmStore::class, $this->container->getSingleton(StoreContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishOrmStore(): void
    {
        $this->container->setSingleton(ManagerContract::class, self::createStub(ManagerContract::class));

        $callback = ServiceProvider::publishers()[OrmStore::class];
        $callback($this->container);

        self::assertInstanceOf(OrmStore::class, $this->container->getSingleton(OrmStore::class));
    }

    public function testPublishInMemoryStore(): void
    {
        $callback = ServiceProvider::publishers()[InMemoryStore::class];
        $callback($this->container);

        self::assertInstanceOf(InMemoryStore::class, $this->container->getSingleton(InMemoryStore::class));
    }

    public function testPublishNullStore(): void
    {
        $callback = ServiceProvider::publishers()[NullStore::class];
        $callback($this->container);

        self::assertInstanceOf(NullStore::class, $this->container->getSingleton(NullStore::class));
    }

    public function testPublishPasswordHasher(): void
    {
        $callback = ServiceProvider::publishers()[PasswordHasherContract::class];
        $callback($this->container);

        self::assertInstanceOf(PhpPasswordHasher::class, $this->container->getSingleton(PasswordHasherContract::class));
    }
}
