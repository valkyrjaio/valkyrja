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
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;
use Valkyrja\Sms\Messenger\LogMessenger;
use Valkyrja\Sms\Messenger\NullMessenger;
use Valkyrja\Sms\Messenger\VonageMessenger;
use Valkyrja\Sms\Provider\SmsServiceProvider;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Credentials\CredentialsInterface;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = SmsServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(MessengerContract::class, new SmsServiceProvider()->publishers());
        self::assertArrayHasKey(VonageMessenger::class, new SmsServiceProvider()->publishers());
        self::assertArrayHasKey(Client::class, new SmsServiceProvider()->publishers());
        self::assertArrayHasKey(CredentialsInterface::class, new SmsServiceProvider()->publishers());
        self::assertArrayHasKey(LogMessenger::class, new SmsServiceProvider()->publishers());
        self::assertArrayHasKey(NullMessenger::class, new SmsServiceProvider()->publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishSms(): void
    {
        $this->container->setSingleton(VonageMessenger::class, self::createStub(VonageMessenger::class));

        $callback = new SmsServiceProvider()->publishers()[MessengerContract::class];
        $callback($this->container);

        self::assertInstanceOf(VonageMessenger::class, $this->container->getSingleton(MessengerContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishVonageSms(): void
    {
        $this->container->setSingleton(Client::class, self::createStub(Client::class));

        $callback = new SmsServiceProvider()->publishers()[VonageMessenger::class];
        $callback($this->container);

        self::assertInstanceOf(VonageMessenger::class, $this->container->getSingleton(VonageMessenger::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishVonage(): void
    {
        $this->container->setSingleton(CredentialsInterface::class, new Basic('', ''));

        $callback = new SmsServiceProvider()->publishers()[Client::class];
        $callback($this->container);

        self::assertInstanceOf(Client::class, $this->container->getSingleton(Client::class));
    }

    public function testPublishVonageCredentials(): void
    {
        $callback = new SmsServiceProvider()->publishers()[CredentialsInterface::class];
        $callback($this->container);

        self::assertInstanceOf(Basic::class, $this->container->getSingleton(CredentialsInterface::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogSms(): void
    {
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        $callback = new SmsServiceProvider()->publishers()[LogMessenger::class];
        $callback($this->container);

        self::assertInstanceOf(LogMessenger::class, $this->container->getSingleton(LogMessenger::class));
    }

    public function testPublishNullSms(): void
    {
        $callback = new SmsServiceProvider()->publishers()[NullMessenger::class];
        $callback($this->container);

        self::assertInstanceOf(NullMessenger::class, $this->container->getSingleton(NullMessenger::class));
    }
}
