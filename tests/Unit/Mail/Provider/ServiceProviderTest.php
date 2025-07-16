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

namespace Valkyrja\Tests\Unit\Mail\Provider;

use Mailgun\HttpClient\HttpClientConfigurator;
use Mailgun\Mailgun;
use PHPMailer\PHPMailer\PHPMailer as PHPMailerClient;
use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Mail\Contract\Mailer as Contract;
use Valkyrja\Mail\LogMailer;
use Valkyrja\Mail\MailgunMailer;
use Valkyrja\Mail\NullMailer;
use Valkyrja\Mail\PhpMailer;
use Valkyrja\Mail\Provider\ServiceProvider;
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
    public function testPublishMailer(): void
    {
        $this->container->setSingleton(MailgunMailer::class, $this->createMock(MailgunMailer::class));

        ServiceProvider::publishMailer($this->container);

        self::assertInstanceOf(MailgunMailer::class, $this->container->getSingleton(Contract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishMailgunMailer(): void
    {
        $this->container->setSingleton(Mailgun::class, $this->createMock(Mailgun::class));

        ServiceProvider::publishMailgunMailer($this->container);

        self::assertInstanceOf(MailgunMailer::class, $this->container->getSingleton(MailgunMailer::class));
    }

    public function testPublishMailgun(): void
    {
        ServiceProvider::publishMailgunHttpClientConfigurator($this->container);
        ServiceProvider::publishMailgun($this->container);

        self::assertInstanceOf(Mailgun::class, $this->container->getSingleton(Mailgun::class));
    }

    public function testPublishMailgunHttpClientConfigurator(): void
    {
        ServiceProvider::publishMailgunHttpClientConfigurator($this->container);

        self::assertInstanceOf(HttpClientConfigurator::class, $this->container->getSingleton(HttpClientConfigurator::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishPhpMailer(): void
    {
        $this->container->setSingleton(PHPMailerClient::class, $this->createMock(PHPMailerClient::class));

        ServiceProvider::publishPhpMailer($this->container);

        self::assertInstanceOf(PhpMailer::class, $this->container->getSingleton(PhpMailer::class));
    }

    public function testPublishPhpMailerClient(): void
    {
        ServiceProvider::publishPhpMailerClient($this->container);

        self::assertInstanceOf(PHPMailerClient::class, $this->container->getSingleton(PHPMailerClient::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogMailer(): void
    {
        $this->container->setSingleton(Logger::class, $this->createMock(Logger::class));

        ServiceProvider::publishLogMailer($this->container);

        self::assertInstanceOf(LogMailer::class, $this->container->getSingleton(LogMailer::class));
    }

    public function testPublishNullMailer(): void
    {
        ServiceProvider::publishNullMailer($this->container);

        self::assertInstanceOf(NullMailer::class, $this->container->getSingleton(NullMailer::class));
    }
}
