<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Crypt\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Crypt\Data\Contract\CryptConfigContract;
use Valkyrja\Crypt\Data\CryptConfig;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Crypt\Manager\NullCrypt;
use Valkyrja\Crypt\Manager\SodiumCrypt;
use Valkyrja\Crypt\Provider\CryptServiceProvider;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Tests\Fixtures\Crypt\Data\CryptConfigFixture;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = CryptServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(CryptConfigContract::class, new CryptServiceProvider()->publishers());
        self::assertArrayHasKey(CryptContract::class, new CryptServiceProvider()->publishers());
        self::assertArrayHasKey(SodiumCrypt::class, new CryptServiceProvider()->publishers());
        self::assertArrayHasKey(NullCrypt::class, new CryptServiceProvider()->publishers());
    }

    public function testPublishConfig(): void
    {
        $callback = new CryptServiceProvider()->publishers()[CryptConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(CryptConfigContract::class, $config = $this->container->getSingleton(CryptConfigContract::class));
        self::assertSame(SodiumCrypt::class, $config->defaultCrypt);
    }

    public function testPublishConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new CryptConfigFixture());

        $callback = new CryptServiceProvider()->publishers()[CryptConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(CryptConfigContract::class, $config = $this->container->getSingleton(CryptConfigContract::class));
        self::assertSame(NullCrypt::class, $config->defaultCrypt);
    }

    /**
     * @throws Exception
     */
    public function testPublishCrypt(): void
    {
        $this->container->setSingleton(CryptConfigContract::class, new CryptConfig());
        $this->container->setSingleton(SodiumCrypt::class, self::createStub(SodiumCrypt::class));

        $callback = new CryptServiceProvider()->publishers()[CryptContract::class];
        $callback($this->container);

        self::assertInstanceOf(SodiumCrypt::class, $this->container->getSingleton(CryptContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishCryptWithConfiguredDefault(): void
    {
        $this->container->setSingleton(CryptConfigContract::class, new CryptConfig(defaultCrypt: NullCrypt::class));
        $this->container->setSingleton(NullCrypt::class, self::createStub(NullCrypt::class));

        $callback = new CryptServiceProvider()->publishers()[CryptContract::class];
        $callback($this->container);

        self::assertInstanceOf(NullCrypt::class, $this->container->getSingleton(CryptContract::class));
    }

    public function testPublishSodiumCrypt(): void
    {
        $callback = new CryptServiceProvider()->publishers()[SodiumCrypt::class];
        $callback($this->container);

        self::assertInstanceOf(SodiumCrypt::class, $this->container->getSingleton(SodiumCrypt::class));
    }

    public function testPublishNullCrypt(): void
    {
        $callback = new CryptServiceProvider()->publishers()[NullCrypt::class];
        $callback($this->container);

        self::assertInstanceOf(NullCrypt::class, $this->container->getSingleton(NullCrypt::class));
    }
}
