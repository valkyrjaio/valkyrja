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

namespace Valkyrja\Tests\Unit\Crypt\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Crypt\Manager\NullCrypt;
use Valkyrja\Crypt\Manager\SodiumCrypt;
use Valkyrja\Crypt\Provider\ServiceProvider;
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
        self::assertArrayHasKey(CryptContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(SodiumCrypt::class, ServiceProvider::publishers());
        self::assertArrayHasKey(NullCrypt::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(CryptContract::class, ServiceProvider::provides());
        self::assertContains(SodiumCrypt::class, ServiceProvider::provides());
        self::assertContains(NullCrypt::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishCrypt(): void
    {
        $this->container->setSingleton(SodiumCrypt::class, self::createStub(SodiumCrypt::class));

        $callback = ServiceProvider::publishers()[CryptContract::class];
        $callback($this->container);

        self::assertInstanceOf(SodiumCrypt::class, $this->container->getSingleton(CryptContract::class));
    }

    public function testPublishSodiumCrypt(): void
    {
        $callback = ServiceProvider::publishers()[SodiumCrypt::class];
        $callback($this->container);

        self::assertInstanceOf(SodiumCrypt::class, $this->container->getSingleton(SodiumCrypt::class));
    }

    public function testPublishNullCrypt(): void
    {
        $callback = ServiceProvider::publishers()[NullCrypt::class];
        $callback($this->container);

        self::assertInstanceOf(NullCrypt::class, $this->container->getSingleton(NullCrypt::class));
    }
}
