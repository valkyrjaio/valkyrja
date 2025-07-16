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

use Monolog\Logger as Monolog;
use PHPUnit\Framework\MockObject\Exception;
use Psr\Log\LoggerInterface;
use Valkyrja\Log\Contract\Logger as Contract;
use Valkyrja\Log\NullLogger;
use Valkyrja\Log\Provider\ServiceProvider;
use Valkyrja\Log\PsrLogger;
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
    public function testPublishLogger(): void
    {
        $this->container->setSingleton(PsrLogger::class, $this->createMock(PsrLogger::class));

        ServiceProvider::publishLogger($this->container);

        self::assertInstanceOf(PsrLogger::class, $this->container->getSingleton(Contract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishPsrLogger(): void
    {
        $this->container->setSingleton(LoggerInterface::class, $this->createMock(LoggerInterface::class));

        ServiceProvider::publishPsrLogger($this->container);

        self::assertInstanceOf(PsrLogger::class, $this->container->getSingleton(PsrLogger::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLoggerInterface(): void
    {
        $this->container->setSingleton(Monolog::class, $this->createMock(Monolog::class));

        ServiceProvider::publishLoggerInterface($this->container);

        self::assertInstanceOf(Monolog::class, $this->container->getSingleton(LoggerInterface::class));
    }

    public function testPublishMonolog(): void
    {
        ServiceProvider::publishMonolog($this->container);

        self::assertInstanceOf(Monolog::class, $this->container->getSingleton(Monolog::class));
    }

    public function testPublishNullLogger(): void
    {
        ServiceProvider::publishNullLogger($this->container);

        self::assertInstanceOf(NullLogger::class, $this->container->getSingleton(NullLogger::class));
    }
}
