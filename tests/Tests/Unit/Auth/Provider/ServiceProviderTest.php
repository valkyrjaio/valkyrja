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
use Valkyrja\Auth\Provider\AuthServiceProvider;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Auth\Store\InMemoryStore;
use Valkyrja\Auth\Store\NullStore;
use Valkyrja\Auth\Store\OrmStore;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Session\Manager\Contract\SessionContract;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = AuthServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(AuthenticatorContract::class, (new AuthServiceProvider())->publishers());
        self::assertArrayHasKey(SessionAuthenticator::class, (new AuthServiceProvider())->publishers());
        self::assertArrayHasKey(StoreContract::class, (new AuthServiceProvider())->publishers());
        self::assertArrayHasKey(OrmStore::class, (new AuthServiceProvider())->publishers());
        self::assertArrayHasKey(InMemoryStore::class, (new AuthServiceProvider())->publishers());
        self::assertArrayHasKey(NullStore::class, (new AuthServiceProvider())->publishers());
        self::assertArrayHasKey(PasswordHasherContract::class, (new AuthServiceProvider())->publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishAuthenticator(): void
    {
        $this->container->setSingleton(SessionAuthenticator::class, self::createStub(SessionAuthenticator::class));

        $callback = (new AuthServiceProvider())->publishers()[AuthenticatorContract::class];
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

        $callback = (new AuthServiceProvider())->publishers()[SessionAuthenticator::class];
        $callback($this->container);

        self::assertInstanceOf(SessionAuthenticator::class, $this->container->getSingleton(SessionAuthenticator::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishStore(): void
    {
        $this->container->setSingleton(OrmStore::class, self::createStub(OrmStore::class));

        $callback = (new AuthServiceProvider())->publishers()[StoreContract::class];
        $callback($this->container);

        self::assertInstanceOf(OrmStore::class, $this->container->getSingleton(StoreContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishOrmStore(): void
    {
        $this->container->setSingleton(ManagerContract::class, self::createStub(ManagerContract::class));

        $callback = (new AuthServiceProvider())->publishers()[OrmStore::class];
        $callback($this->container);

        self::assertInstanceOf(OrmStore::class, $this->container->getSingleton(OrmStore::class));
    }

    public function testPublishInMemoryStore(): void
    {
        $callback = (new AuthServiceProvider())->publishers()[InMemoryStore::class];
        $callback($this->container);

        self::assertInstanceOf(InMemoryStore::class, $this->container->getSingleton(InMemoryStore::class));
    }

    public function testPublishNullStore(): void
    {
        $callback = (new AuthServiceProvider())->publishers()[NullStore::class];
        $callback($this->container);

        self::assertInstanceOf(NullStore::class, $this->container->getSingleton(NullStore::class));
    }

    public function testPublishPasswordHasher(): void
    {
        $callback = (new AuthServiceProvider())->publishers()[PasswordHasherContract::class];
        $callback($this->container);

        self::assertInstanceOf(PhpPasswordHasher::class, $this->container->getSingleton(PasswordHasherContract::class));
    }
}
