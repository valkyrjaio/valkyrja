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

namespace Valkyrja\Tests\Unit\Session\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Session\Data\CookieParams;
use Valkyrja\Session\Manager\CacheSession;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\Cookie\CookieSession;
use Valkyrja\Session\Manager\Cookie\EncryptedCookieSession;
use Valkyrja\Session\Manager\Jwt\EncryptedJwtSession;
use Valkyrja\Session\Manager\Jwt\JwtSession;
use Valkyrja\Session\Manager\LogSession;
use Valkyrja\Session\Manager\NullSession;
use Valkyrja\Session\Manager\PhpSession;
use Valkyrja\Session\Manager\Token\EncryptedTokenSession;
use Valkyrja\Session\Manager\Token\TokenSession;
use Valkyrja\Session\Provider\ServiceProvider;
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
        self::assertArrayHasKey(SessionContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(PhpSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(NullSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CacheSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CookieSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(EncryptedCookieSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(JwtSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(EncryptedJwtSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(TokenSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(EncryptedTokenSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(LogSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CookieParams::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(SessionContract::class, ServiceProvider::provides());
        self::assertContains(PhpSession::class, ServiceProvider::provides());
        self::assertContains(NullSession::class, ServiceProvider::provides());
        self::assertContains(CacheSession::class, ServiceProvider::provides());
        self::assertContains(CookieSession::class, ServiceProvider::provides());
        self::assertContains(EncryptedCookieSession::class, ServiceProvider::provides());
        self::assertContains(JwtSession::class, ServiceProvider::provides());
        self::assertContains(EncryptedJwtSession::class, ServiceProvider::provides());
        self::assertContains(TokenSession::class, ServiceProvider::provides());
        self::assertContains(EncryptedTokenSession::class, ServiceProvider::provides());
        self::assertContains(LogSession::class, ServiceProvider::provides());
        self::assertContains(CookieParams::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishSession(): void
    {
        $this->container->setSingleton(PhpSession::class, self::createStub(PhpSession::class));

        $callback = ServiceProvider::publishers()[SessionContract::class];
        $callback($this->container);

        self::assertInstanceOf(PhpSession::class, $this->container->getSingleton(SessionContract::class));
    }

    public function testPublishPhpSession(): void
    {
        $this->container->setSingleton(CookieParams::class, new CookieParams());

        $callback = ServiceProvider::publishers()[PhpSession::class];
        $callback($this->container);

        self::assertInstanceOf(PhpSession::class, $this->container->getSingleton(PhpSession::class));
    }

    public function testPublishNullSession(): void
    {
        $callback = ServiceProvider::publishers()[NullSession::class];
        $callback($this->container);

        self::assertInstanceOf(NullSession::class, $this->container->getSingleton(NullSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishCacheSession(): void
    {
        $this->container->setSingleton(CacheContract::class, self::createStub(CacheContract::class));

        $callback = ServiceProvider::publishers()[CacheSession::class];
        $callback($this->container);

        self::assertInstanceOf(CacheSession::class, $this->container->getSingleton(CacheSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishCookieSession(): void
    {
        $this->container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));

        $callback = ServiceProvider::publishers()[CookieSession::class];
        $callback($this->container);

        self::assertInstanceOf(CookieSession::class, $this->container->getSingleton(CookieSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishEncryptedCookieSession(): void
    {
        $this->container->setSingleton(CryptContract::class, self::createStub(CryptContract::class));
        $this->container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));

        $callback = ServiceProvider::publishers()[EncryptedCookieSession::class];
        $callback($this->container);

        self::assertInstanceOf(EncryptedCookieSession::class, $this->container->getSingleton(EncryptedCookieSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishJwtSession(): void
    {
        $this->container->setSingleton(JwtContract::class, self::createStub(JwtContract::class));
        $this->container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));

        $callback = ServiceProvider::publishers()[JwtSession::class];
        $callback($this->container);

        self::assertInstanceOf(JwtSession::class, $this->container->getSingleton(JwtSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishEncryptedJwtSession(): void
    {
        $this->container->setSingleton(CryptContract::class, self::createStub(CryptContract::class));
        $this->container->setSingleton(JwtContract::class, self::createStub(JwtContract::class));
        $this->container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));

        $callback = ServiceProvider::publishers()[EncryptedJwtSession::class];
        $callback($this->container);

        self::assertInstanceOf(EncryptedJwtSession::class, $this->container->getSingleton(EncryptedJwtSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishTokenSession(): void
    {
        $this->container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));

        $callback = ServiceProvider::publishers()[TokenSession::class];
        $callback($this->container);

        self::assertInstanceOf(TokenSession::class, $this->container->getSingleton(TokenSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishEncryptedTokenSession(): void
    {
        $this->container->setSingleton(CryptContract::class, self::createStub(CryptContract::class));
        $this->container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));

        $callback = ServiceProvider::publishers()[EncryptedTokenSession::class];
        $callback($this->container);

        self::assertInstanceOf(EncryptedTokenSession::class, $this->container->getSingleton(EncryptedTokenSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogSession(): void
    {
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        $callback = ServiceProvider::publishers()[LogSession::class];
        $callback($this->container);

        self::assertInstanceOf(LogSession::class, $this->container->getSingleton(LogSession::class));
    }

    public function testPublishCookieParams(): void
    {
        $callback = ServiceProvider::publishers()[CookieParams::class];
        $callback($this->container);

        self::assertInstanceOf(CookieParams::class, $cookieParams = $this->container->getSingleton(CookieParams::class));
        self::assertSame(Env::SESSION_COOKIE_PARAM_PATH, $cookieParams->path);
        self::assertSame(Env::SESSION_COOKIE_PARAM_DOMAIN, $cookieParams->domain);
        self::assertSame(Env::SESSION_COOKIE_PARAM_LIFETIME, $cookieParams->lifetime);
        self::assertSame(Env::SESSION_COOKIE_PARAM_SECURE, $cookieParams->secure);
        self::assertSame(Env::SESSION_COOKIE_PARAM_HTTP_ONLY, $cookieParams->httpOnly);
        self::assertSame(Env::SESSION_COOKIE_PARAM_SAME_SITE, $cookieParams->sameSite);
    }
}
