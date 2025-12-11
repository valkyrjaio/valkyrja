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
use Valkyrja\Application\Env;
use Valkyrja\Cache\Contract\Cache;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Session\CacheSession;
use Valkyrja\Session\Contract\Session as Contract;
use Valkyrja\Session\CookieSession;
use Valkyrja\Session\Data\CookieParams;
use Valkyrja\Session\LogSession;
use Valkyrja\Session\NullSession;
use Valkyrja\Session\PhpSession;
use Valkyrja\Session\Provider\ServiceProvider;
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
    public function testPublishSession(): void
    {
        $this->container->setSingleton(PhpSession::class, self::createStub(PhpSession::class));

        ServiceProvider::publishSession($this->container);

        self::assertInstanceOf(PhpSession::class, $this->container->getSingleton(Contract::class));
    }

    public function testPublishPhpSession(): void
    {
        $this->container->setSingleton(CookieParams::class, new CookieParams());

        ServiceProvider::publishPhpSession($this->container);

        self::assertInstanceOf(PhpSession::class, $this->container->getSingleton(PhpSession::class));
    }

    public function testPublishNullSession(): void
    {
        ServiceProvider::publishNullSession($this->container);

        self::assertInstanceOf(NullSession::class, $this->container->getSingleton(NullSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishCacheSession(): void
    {
        $this->container->setSingleton(Cache::class, self::createStub(Cache::class));
        $this->container->setSingleton(CookieParams::class, new CookieParams());

        ServiceProvider::publishCacheSession($this->container);

        self::assertInstanceOf(CacheSession::class, $this->container->getSingleton(CacheSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishCookieSession(): void
    {
        $this->container->setSingleton(Crypt::class, self::createStub(Crypt::class));
        $this->container->setSingleton(ServerRequest::class, self::createStub(ServerRequest::class));
        $this->container->setSingleton(CookieParams::class, new CookieParams());

        ServiceProvider::publishCookieSession($this->container);

        self::assertInstanceOf(CookieSession::class, $this->container->getSingleton(CookieSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogSession(): void
    {
        $this->container->setSingleton(Logger::class, self::createStub(Logger::class));
        $this->container->setSingleton(CookieParams::class, new CookieParams());

        ServiceProvider::publishLogSession($this->container);

        self::assertInstanceOf(LogSession::class, $this->container->getSingleton(LogSession::class));
    }

    public function testPublishCookieParams(): void
    {
        ServiceProvider::publishCookieParams($this->container);

        self::assertInstanceOf(CookieParams::class, $cookieParams = $this->container->getSingleton(CookieParams::class));
        self::assertSame(Env::SESSION_COOKIE_PARAM_PATH, $cookieParams->path);
        self::assertSame(Env::SESSION_COOKIE_PARAM_DOMAIN, $cookieParams->domain);
        self::assertSame(Env::SESSION_COOKIE_PARAM_LIFETIME, $cookieParams->lifetime);
        self::assertSame(Env::SESSION_COOKIE_PARAM_SECURE, $cookieParams->secure);
        self::assertSame(Env::SESSION_COOKIE_PARAM_HTTP_ONLY, $cookieParams->httpOnly);
        self::assertSame(Env::SESSION_COOKIE_PARAM_SAME_SITE, $cookieParams->sameSite);
    }
}
