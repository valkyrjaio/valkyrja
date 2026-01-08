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
use Valkyrja\Auth\Authenticator\EncryptedJwtAuthenticator;
use Valkyrja\Auth\Authenticator\EncryptedTokenAuthenticator;
use Valkyrja\Auth\Authenticator\JwtAuthenticator;
use Valkyrja\Auth\Authenticator\SessionAuthenticator;
use Valkyrja\Auth\Authenticator\TokenAuthenticator;
use Valkyrja\Auth\Hasher\Contract\PasswordHasherContract;
use Valkyrja\Auth\Hasher\PhpPasswordHasher;
use Valkyrja\Auth\Provider\ServiceProvider;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Auth\Store\InMemoryStore;
use Valkyrja\Auth\Store\NullStore;
use Valkyrja\Auth\Store\OrmStore;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

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
        self::assertArrayHasKey(EncryptedJwtAuthenticator::class, ServiceProvider::publishers());
        self::assertArrayHasKey(EncryptedTokenAuthenticator::class, ServiceProvider::publishers());
        self::assertArrayHasKey(JwtAuthenticator::class, ServiceProvider::publishers());
        self::assertArrayHasKey(SessionAuthenticator::class, ServiceProvider::publishers());
        self::assertArrayHasKey(TokenAuthenticator::class, ServiceProvider::publishers());
        self::assertArrayHasKey(StoreContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(OrmStore::class, ServiceProvider::publishers());
        self::assertArrayHasKey(InMemoryStore::class, ServiceProvider::publishers());
        self::assertArrayHasKey(NullStore::class, ServiceProvider::publishers());
        self::assertArrayHasKey(PasswordHasherContract::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(AuthenticatorContract::class, ServiceProvider::provides());
        self::assertContains(EncryptedJwtAuthenticator::class, ServiceProvider::provides());
        self::assertContains(EncryptedTokenAuthenticator::class, ServiceProvider::provides());
        self::assertContains(JwtAuthenticator::class, ServiceProvider::provides());
        self::assertContains(SessionAuthenticator::class, ServiceProvider::provides());
        self::assertContains(TokenAuthenticator::class, ServiceProvider::provides());
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
    public function testPublishEncryptedJwtAuthenticator(): void
    {
        $this->container->setSingleton(CryptContract::class, self::createStub(CryptContract::class));
        $this->container->setSingleton(JwtContract::class, self::createStub(JwtContract::class));
        $this->container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));
        $this->container->setSingleton(StoreContract::class, self::createStub(StoreContract::class));
        $this->container->setSingleton(PasswordHasherContract::class, self::createStub(PasswordHasherContract::class));

        $callback = ServiceProvider::publishers()[EncryptedJwtAuthenticator::class];
        $callback($this->container);

        self::assertInstanceOf(EncryptedJwtAuthenticator::class, $this->container->getSingleton(EncryptedJwtAuthenticator::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishEncryptedTokenAuthenticator(): void
    {
        $this->container->setSingleton(CryptContract::class, self::createStub(CryptContract::class));
        $this->container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));
        $this->container->setSingleton(StoreContract::class, self::createStub(StoreContract::class));
        $this->container->setSingleton(PasswordHasherContract::class, self::createStub(PasswordHasherContract::class));

        $callback = ServiceProvider::publishers()[EncryptedTokenAuthenticator::class];
        $callback($this->container);

        self::assertInstanceOf(EncryptedTokenAuthenticator::class, $this->container->getSingleton(EncryptedTokenAuthenticator::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishJwtAuthenticator(): void
    {
        $this->container->setSingleton(JwtContract::class, self::createStub(JwtContract::class));
        $this->container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));
        $this->container->setSingleton(StoreContract::class, self::createStub(StoreContract::class));
        $this->container->setSingleton(PasswordHasherContract::class, self::createStub(PasswordHasherContract::class));

        $callback = ServiceProvider::publishers()[JwtAuthenticator::class];
        $callback($this->container);

        self::assertInstanceOf(JwtAuthenticator::class, $this->container->getSingleton(JwtAuthenticator::class));
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
    public function testPublishTokenAuthenticator(): void
    {
        $this->container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));
        $this->container->setSingleton(StoreContract::class, self::createStub(StoreContract::class));
        $this->container->setSingleton(PasswordHasherContract::class, self::createStub(PasswordHasherContract::class));

        $callback = ServiceProvider::publishers()[TokenAuthenticator::class];
        $callback($this->container);

        self::assertInstanceOf(TokenAuthenticator::class, $this->container->getSingleton(TokenAuthenticator::class));
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
