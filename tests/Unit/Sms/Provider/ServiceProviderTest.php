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
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Sms\Messenger\Contract\Messenger as Contract;
use Valkyrja\Sms\Messenger\LogMessenger;
use Valkyrja\Sms\Messenger\NullMessenger;
use Valkyrja\Sms\Messenger\VonageMessenger;
use Valkyrja\Sms\Provider\ServiceProvider;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;
use Vonage\Client as Vonage;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Credentials\CredentialsInterface as VonageCredentials;

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
    public function testPublishSms(): void
    {
        $this->container->setSingleton(VonageMessenger::class, self::createStub(VonageMessenger::class));

        ServiceProvider::publishSms($this->container);

        self::assertInstanceOf(VonageMessenger::class, $this->container->getSingleton(Contract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishVonageSms(): void
    {
        $this->container->setSingleton(Vonage::class, self::createStub(Vonage::class));

        ServiceProvider::publishVonageSms($this->container);

        self::assertInstanceOf(VonageMessenger::class, $this->container->getSingleton(VonageMessenger::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishVonage(): void
    {
        $this->container->setSingleton(VonageCredentials::class, new Basic('', ''));

        ServiceProvider::publishVonage($this->container);

        self::assertInstanceOf(Vonage::class, $this->container->getSingleton(Vonage::class));
    }

    public function testPublishVonageCredentials(): void
    {
        ServiceProvider::publishVonageCredentials($this->container);

        self::assertInstanceOf(Basic::class, $this->container->getSingleton(VonageCredentials::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogSms(): void
    {
        $this->container->setSingleton(Logger::class, self::createStub(Logger::class));

        ServiceProvider::publishLogSms($this->container);

        self::assertInstanceOf(LogMessenger::class, $this->container->getSingleton(LogMessenger::class));
    }

    public function testPublishNullSms(): void
    {
        ServiceProvider::publishNullSms($this->container);

        self::assertInstanceOf(NullMessenger::class, $this->container->getSingleton(NullMessenger::class));
    }
}
