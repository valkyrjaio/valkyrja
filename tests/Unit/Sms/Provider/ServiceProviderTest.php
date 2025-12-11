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
use Valkyrja\Sms\Contract\Sms as Contract;
use Valkyrja\Sms\LogSms;
use Valkyrja\Sms\NullSms;
use Valkyrja\Sms\Provider\ServiceProvider;
use Valkyrja\Sms\VonageSms;
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
        $this->container->setSingleton(VonageSms::class, $this->createStub(VonageSms::class));

        ServiceProvider::publishSms($this->container);

        self::assertInstanceOf(VonageSms::class, $this->container->getSingleton(Contract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishVonageSms(): void
    {
        $this->container->setSingleton(Vonage::class, $this->createStub(Vonage::class));

        ServiceProvider::publishVonageSms($this->container);

        self::assertInstanceOf(VonageSms::class, $this->container->getSingleton(VonageSms::class));
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
        $this->container->setSingleton(Logger::class, $this->createStub(Logger::class));

        ServiceProvider::publishLogSms($this->container);

        self::assertInstanceOf(LogSms::class, $this->container->getSingleton(LogSms::class));
    }

    public function testPublishNullSms(): void
    {
        ServiceProvider::publishNullSms($this->container);

        self::assertInstanceOf(NullSms::class, $this->container->getSingleton(NullSms::class));
    }
}
