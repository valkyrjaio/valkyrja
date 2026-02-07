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

namespace Valkyrja\Tests\Unit\Sms\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;
use Valkyrja\Sms\Messenger\LogMessenger;
use Valkyrja\Sms\Messenger\NullMessenger;
use Valkyrja\Sms\Messenger\VonageMessenger;
use Valkyrja\Sms\Provider\ServiceProvider;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Credentials\CredentialsInterface;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(MessengerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(VonageMessenger::class, ServiceProvider::publishers());
        self::assertArrayHasKey(Client::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CredentialsInterface::class, ServiceProvider::publishers());
        self::assertArrayHasKey(LogMessenger::class, ServiceProvider::publishers());
        self::assertArrayHasKey(NullMessenger::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(MessengerContract::class, ServiceProvider::provides());
        self::assertContains(VonageMessenger::class, ServiceProvider::provides());
        self::assertContains(Client::class, ServiceProvider::provides());
        self::assertContains(CredentialsInterface::class, ServiceProvider::provides());
        self::assertContains(LogMessenger::class, ServiceProvider::provides());
        self::assertContains(NullMessenger::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishSms(): void
    {
        $this->container->setSingleton(VonageMessenger::class, self::createStub(VonageMessenger::class));

        $callback = ServiceProvider::publishers()[MessengerContract::class];
        $callback($this->container);

        self::assertInstanceOf(VonageMessenger::class, $this->container->getSingleton(MessengerContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishVonageSms(): void
    {
        $this->container->setSingleton(Client::class, self::createStub(Client::class));

        $callback = ServiceProvider::publishers()[VonageMessenger::class];
        $callback($this->container);

        self::assertInstanceOf(VonageMessenger::class, $this->container->getSingleton(VonageMessenger::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishVonage(): void
    {
        $this->container->setSingleton(CredentialsInterface::class, new Basic('', ''));

        $callback = ServiceProvider::publishers()[Client::class];
        $callback($this->container);

        self::assertInstanceOf(Client::class, $this->container->getSingleton(Client::class));
    }

    public function testPublishVonageCredentials(): void
    {
        $callback = ServiceProvider::publishers()[CredentialsInterface::class];
        $callback($this->container);

        self::assertInstanceOf(Basic::class, $this->container->getSingleton(CredentialsInterface::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogSms(): void
    {
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        $callback = ServiceProvider::publishers()[LogMessenger::class];
        $callback($this->container);

        self::assertInstanceOf(LogMessenger::class, $this->container->getSingleton(LogMessenger::class));
    }

    public function testPublishNullSms(): void
    {
        $callback = ServiceProvider::publishers()[NullMessenger::class];
        $callback($this->container);

        self::assertInstanceOf(NullMessenger::class, $this->container->getSingleton(NullMessenger::class));
    }
}
