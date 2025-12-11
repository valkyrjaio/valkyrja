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
use Valkyrja\Auth\Contract\Authenticator as Contract;
use Valkyrja\Auth\EncryptedJwtAuthenticator;
use Valkyrja\Auth\EncryptedTokenAuthenticator;
use Valkyrja\Auth\Hasher\Contract\PasswordHasher;
use Valkyrja\Auth\Hasher\PhpPasswordHasher;
use Valkyrja\Auth\JwtAuthenticator;
use Valkyrja\Auth\Provider\ServiceProvider;
use Valkyrja\Auth\SessionAuthenticator;
use Valkyrja\Auth\Store\Contract\Store;
use Valkyrja\Auth\Store\InMemoryStore;
use Valkyrja\Auth\Store\NullStore;
use Valkyrja\Auth\Store\OrmStore;
use Valkyrja\Auth\TokenAuthenticator;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Jwt\Contract\Jwt;
use Valkyrja\Orm\Contract\Manager;
use Valkyrja\Session\Contract\Session;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    /**
     * @throws Exception
     */
    public function testPublishAuthenticator(): void
    {
        $this->container->setSingleton(SessionAuthenticator::class, $this->createStub(SessionAuthenticator::class));

        ServiceProvider::publishAuthenticator($this->container);

        self::assertInstanceOf(SessionAuthenticator::class, $this->container->getSingleton(Contract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishEncryptedJwtAuthenticator(): void
    {
        $this->container->setSingleton(Crypt::class, $this->createStub(Crypt::class));
        $this->container->setSingleton(Jwt::class, $this->createStub(Jwt::class));
        $this->container->setSingleton(ServerRequest::class, $this->createStub(ServerRequest::class));
        $this->container->setSingleton(Store::class, $this->createStub(Store::class));
        $this->container->setSingleton(PasswordHasher::class, $this->createStub(PasswordHasher::class));

        ServiceProvider::publishEncryptedJwtAuthenticator($this->container);

        self::assertInstanceOf(EncryptedJwtAuthenticator::class, $this->container->getSingleton(EncryptedJwtAuthenticator::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishEncryptedTokenAuthenticator(): void
    {
        $this->container->setSingleton(Crypt::class, $this->createStub(Crypt::class));
        $this->container->setSingleton(ServerRequest::class, $this->createStub(ServerRequest::class));
        $this->container->setSingleton(Store::class, $this->createStub(Store::class));
        $this->container->setSingleton(PasswordHasher::class, $this->createStub(PasswordHasher::class));

        ServiceProvider::publishEncryptedTokenAuthenticator($this->container);

        self::assertInstanceOf(EncryptedTokenAuthenticator::class, $this->container->getSingleton(EncryptedTokenAuthenticator::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishJwtAuthenticator(): void
    {
        $this->container->setSingleton(Jwt::class, $this->createStub(Jwt::class));
        $this->container->setSingleton(ServerRequest::class, $this->createStub(ServerRequest::class));
        $this->container->setSingleton(Store::class, $this->createStub(Store::class));
        $this->container->setSingleton(PasswordHasher::class, $this->createStub(PasswordHasher::class));

        ServiceProvider::publishJwtAuthenticator($this->container);

        self::assertInstanceOf(JwtAuthenticator::class, $this->container->getSingleton(JwtAuthenticator::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishSessionAuthenticator(): void
    {
        $this->container->setSingleton(Session::class, $this->createStub(Session::class));
        $this->container->setSingleton(Store::class, $this->createStub(Store::class));
        $this->container->setSingleton(PasswordHasher::class, $this->createStub(PasswordHasher::class));

        ServiceProvider::publishSessionAuthenticator($this->container);

        self::assertInstanceOf(SessionAuthenticator::class, $this->container->getSingleton(SessionAuthenticator::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishTokenAuthenticator(): void
    {
        $this->container->setSingleton(ServerRequest::class, $this->createStub(ServerRequest::class));
        $this->container->setSingleton(Store::class, $this->createStub(Store::class));
        $this->container->setSingleton(PasswordHasher::class, $this->createStub(PasswordHasher::class));

        ServiceProvider::publishTokenAuthenticator($this->container);

        self::assertInstanceOf(TokenAuthenticator::class, $this->container->getSingleton(TokenAuthenticator::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishStore(): void
    {
        $this->container->setSingleton(OrmStore::class, $this->createStub(OrmStore::class));

        ServiceProvider::publishStore($this->container);

        self::assertInstanceOf(OrmStore::class, $this->container->getSingleton(Store::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishOrmStore(): void
    {
        $this->container->setSingleton(Manager::class, $this->createStub(Manager::class));

        ServiceProvider::publishOrmStore($this->container);

        self::assertInstanceOf(OrmStore::class, $this->container->getSingleton(OrmStore::class));
    }

    public function testPublishInMemoryStore(): void
    {
        ServiceProvider::publishInMemoryStore($this->container);

        self::assertInstanceOf(InMemoryStore::class, $this->container->getSingleton(InMemoryStore::class));
    }

    public function testPublishNullStore(): void
    {
        ServiceProvider::publishNullStore($this->container);

        self::assertInstanceOf(NullStore::class, $this->container->getSingleton(NullStore::class));
    }

    public function testPublishPasswordHasher(): void
    {
        ServiceProvider::publishPasswordHasher($this->container);

        self::assertInstanceOf(PhpPasswordHasher::class, $this->container->getSingleton(PasswordHasher::class));
    }
}
