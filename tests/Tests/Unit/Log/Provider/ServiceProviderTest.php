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
use Valkyrja\Log\Provider\LogServiceProvider;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = LogServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(LoggerContract::class, new LogServiceProvider()->publishers());
        self::assertArrayHasKey(PsrLogger::class, new LogServiceProvider()->publishers());
        self::assertArrayHasKey(NullLogger::class, new LogServiceProvider()->publishers());
        self::assertArrayHasKey(LoggerInterface::class, new LogServiceProvider()->publishers());
        self::assertArrayHasKey(Logger::class, new LogServiceProvider()->publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishLogger(): void
    {
        $this->container->setSingleton(PsrLogger::class, self::createStub(PsrLogger::class));

        $callback = new LogServiceProvider()->publishers()[LoggerContract::class];
        $callback($this->container);

        self::assertInstanceOf(PsrLogger::class, $this->container->getSingleton(LoggerContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishPsrLogger(): void
    {
        $this->container->setSingleton(LoggerInterface::class, self::createStub(LoggerInterface::class));

        $callback = new LogServiceProvider()->publishers()[PsrLogger::class];
        $callback($this->container);

        self::assertInstanceOf(PsrLogger::class, $this->container->getSingleton(PsrLogger::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLoggerInterface(): void
    {
        $this->container->setSingleton(Logger::class, self::createStub(Logger::class));

        $callback = new LogServiceProvider()->publishers()[LoggerInterface::class];
        $callback($this->container);

        self::assertInstanceOf(Logger::class, $this->container->getSingleton(LoggerInterface::class));
    }

    public function testPublishMonolog(): void
    {
        $callback = new LogServiceProvider()->publishers()[Logger::class];
        $callback($this->container);

        self::assertInstanceOf(Logger::class, $this->container->getSingleton(Logger::class));
    }

    public function testPublishNullLogger(): void
    {
        $callback = new LogServiceProvider()->publishers()[NullLogger::class];
        $callback($this->container);

        self::assertInstanceOf(NullLogger::class, $this->container->getSingleton(NullLogger::class));
    }
}
