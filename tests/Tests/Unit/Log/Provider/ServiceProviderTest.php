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

namespace Valkyrja\Tests\Unit\Log\Provider;

use Monolog\Logger;
use PHPUnit\Framework\MockObject\Exception;
use Psr\Log\LoggerInterface;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Log\Logger\NullLogger;
use Valkyrja\Log\Logger\PsrLogger;
use Valkyrja\Log\Provider\ServiceProvider;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(LoggerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(PsrLogger::class, ServiceProvider::publishers());
        self::assertArrayHasKey(NullLogger::class, ServiceProvider::publishers());
        self::assertArrayHasKey(LoggerInterface::class, ServiceProvider::publishers());
        self::assertArrayHasKey(Logger::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(LoggerContract::class, ServiceProvider::provides());
        self::assertContains(PsrLogger::class, ServiceProvider::provides());
        self::assertContains(NullLogger::class, ServiceProvider::provides());
        self::assertContains(LoggerInterface::class, ServiceProvider::provides());
        self::assertContains(Logger::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishLogger(): void
    {
        $this->container->setSingleton(PsrLogger::class, self::createStub(PsrLogger::class));

        $callback = ServiceProvider::publishers()[LoggerContract::class];
        $callback($this->container);

        self::assertInstanceOf(PsrLogger::class, $this->container->getSingleton(LoggerContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishPsrLogger(): void
    {
        $this->container->setSingleton(LoggerInterface::class, self::createStub(LoggerInterface::class));

        $callback = ServiceProvider::publishers()[PsrLogger::class];
        $callback($this->container);

        self::assertInstanceOf(PsrLogger::class, $this->container->getSingleton(PsrLogger::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLoggerInterface(): void
    {
        $this->container->setSingleton(Logger::class, self::createStub(Logger::class));

        $callback = ServiceProvider::publishers()[LoggerInterface::class];
        $callback($this->container);

        self::assertInstanceOf(Logger::class, $this->container->getSingleton(LoggerInterface::class));
    }

    public function testPublishMonolog(): void
    {
        $callback = ServiceProvider::publishers()[Logger::class];
        $callback($this->container);

        self::assertInstanceOf(Logger::class, $this->container->getSingleton(Logger::class));
    }

    public function testPublishNullLogger(): void
    {
        $callback = ServiceProvider::publishers()[NullLogger::class];
        $callback($this->container);

        self::assertInstanceOf(NullLogger::class, $this->container->getSingleton(NullLogger::class));
    }
}
