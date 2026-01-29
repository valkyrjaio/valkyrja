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
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Session\Data\CookieParams;
use Valkyrja\Session\Manager\CacheSession;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\Cookie\CookieSession;
use Valkyrja\Session\Manager\Cookie\EncryptedCookieSession;
use Valkyrja\Session\Manager\Jwt\Cli\EncryptedOptionJwtSession;
use Valkyrja\Session\Manager\Jwt\Cli\OptionJwtSession;
use Valkyrja\Session\Manager\Jwt\Http\EncryptedHeaderJwtSession;
use Valkyrja\Session\Manager\Jwt\Http\HeaderJwtSession;
use Valkyrja\Session\Manager\LogSession;
use Valkyrja\Session\Manager\NullSession;
use Valkyrja\Session\Manager\PhpSession;
use Valkyrja\Session\Manager\Token\Cli\EncryptedOptionTokenSession;
use Valkyrja\Session\Manager\Token\Cli\OptionTokenSession;
use Valkyrja\Session\Manager\Token\Http\EncryptedHeaderTokenSession;
use Valkyrja\Session\Manager\Token\Http\HeaderTokenSession;
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
        self::assertArrayHasKey(OptionJwtSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(EncryptedOptionJwtSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(HeaderJwtSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(EncryptedHeaderJwtSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(OptionTokenSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(EncryptedOptionTokenSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(HeaderTokenSession::class, ServiceProvider::publishers());
        self::assertArrayHasKey(EncryptedHeaderTokenSession::class, ServiceProvider::publishers());
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
        self::assertContains(OptionJwtSession::class, ServiceProvider::provides());
        self::assertContains(EncryptedOptionJwtSession::class, ServiceProvider::provides());
        self::assertContains(HeaderJwtSession::class, ServiceProvider::provides());
        self::assertContains(EncryptedHeaderJwtSession::class, ServiceProvider::provides());
        self::assertContains(OptionTokenSession::class, ServiceProvider::provides());
        self::assertContains(EncryptedOptionTokenSession::class, ServiceProvider::provides());
        self::assertContains(HeaderTokenSession::class, ServiceProvider::provides());
        self::assertContains(EncryptedHeaderTokenSession::class, ServiceProvider::provides());
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
    public function testPublishOptionJwtSession(): void
    {
        $this->container->setSingleton(JwtContract::class, self::createStub(JwtContract::class));
        $this->container->setSingleton(InputContract::class, self::createStub(InputContract::class));

        $callback = ServiceProvider::publishers()[OptionJwtSession::class];
        $callback($this->container);

        self::assertInstanceOf(OptionJwtSession::class, $this->container->getSingleton(OptionJwtSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishEncryptedOptionJwtSession(): void
    {
        $this->container->setSingleton(CryptContract::class, self::createStub(CryptContract::class));
        $this->container->setSingleton(JwtContract::class, self::createStub(JwtContract::class));
        $this->container->setSingleton(InputContract::class, self::createStub(InputContract::class));

        $callback = ServiceProvider::publishers()[EncryptedOptionJwtSession::class];
        $callback($this->container);

        self::assertInstanceOf(EncryptedOptionJwtSession::class, $this->container->getSingleton(EncryptedOptionJwtSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishHeaderJwtSession(): void
    {
        $this->container->setSingleton(JwtContract::class, self::createStub(JwtContract::class));
        $this->container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));

        $callback = ServiceProvider::publishers()[HeaderJwtSession::class];
        $callback($this->container);

        self::assertInstanceOf(HeaderJwtSession::class, $this->container->getSingleton(HeaderJwtSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishEncryptedHeaderJwtSession(): void
    {
        $this->container->setSingleton(CryptContract::class, self::createStub(CryptContract::class));
        $this->container->setSingleton(JwtContract::class, self::createStub(JwtContract::class));
        $this->container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));

        $callback = ServiceProvider::publishers()[EncryptedHeaderJwtSession::class];
        $callback($this->container);

        self::assertInstanceOf(EncryptedHeaderJwtSession::class, $this->container->getSingleton(EncryptedHeaderJwtSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishOptionTokenSession(): void
    {
        $this->container->setSingleton(InputContract::class, self::createStub(InputContract::class));

        $callback = ServiceProvider::publishers()[OptionTokenSession::class];
        $callback($this->container);

        self::assertInstanceOf(OptionTokenSession::class, $this->container->getSingleton(OptionTokenSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishEncryptedOptionTokenSession(): void
    {
        $this->container->setSingleton(CryptContract::class, self::createStub(CryptContract::class));
        $this->container->setSingleton(InputContract::class, self::createStub(InputContract::class));

        $callback = ServiceProvider::publishers()[EncryptedOptionTokenSession::class];
        $callback($this->container);

        self::assertInstanceOf(EncryptedOptionTokenSession::class, $this->container->getSingleton(EncryptedOptionTokenSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishHeaderTokenSession(): void
    {
        $this->container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));

        $callback = ServiceProvider::publishers()[HeaderTokenSession::class];
        $callback($this->container);

        self::assertInstanceOf(HeaderTokenSession::class, $this->container->getSingleton(HeaderTokenSession::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishEncryptedHeaderTokenSession(): void
    {
        $this->container->setSingleton(CryptContract::class, self::createStub(CryptContract::class));
        $this->container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));

        $callback = ServiceProvider::publishers()[EncryptedHeaderTokenSession::class];
        $callback($this->container);

        self::assertInstanceOf(EncryptedHeaderTokenSession::class, $this->container->getSingleton(EncryptedHeaderTokenSession::class));
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
        self::assertSame('/', $cookieParams->path);
        self::assertNull($cookieParams->domain);
        self::assertSame(0, $cookieParams->lifetime);
        self::assertFalse($cookieParams->secure);
        self::assertFalse($cookieParams->httpOnly);
        self::assertSame(SameSite::NONE, $cookieParams->sameSite);
    }
}
