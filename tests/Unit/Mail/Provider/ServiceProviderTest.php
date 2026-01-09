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
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Mail\Mailer\Contract\MailerContract;
use Valkyrja\Mail\Mailer\LogMailer;
use Valkyrja\Mail\Mailer\MailgunMailer;
use Valkyrja\Mail\Mailer\NullMailer;
use Valkyrja\Mail\Mailer\PhpMailer;
use Valkyrja\Mail\Provider\ServiceProvider;
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
        self::assertArrayHasKey(MailerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(MailgunMailer::class, ServiceProvider::publishers());
        self::assertArrayHasKey(Mailgun::class, ServiceProvider::publishers());
        self::assertArrayHasKey(HttpClientConfigurator::class, ServiceProvider::publishers());
        self::assertArrayHasKey(PhpMailer::class, ServiceProvider::publishers());
        self::assertArrayHasKey(PHPMailerClient::class, ServiceProvider::publishers());
        self::assertArrayHasKey(LogMailer::class, ServiceProvider::publishers());
        self::assertArrayHasKey(NullMailer::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(MailerContract::class, ServiceProvider::provides());
        self::assertContains(MailgunMailer::class, ServiceProvider::provides());
        self::assertContains(Mailgun::class, ServiceProvider::provides());
        self::assertContains(HttpClientConfigurator::class, ServiceProvider::provides());
        self::assertContains(PhpMailer::class, ServiceProvider::provides());
        self::assertContains(PHPMailerClient::class, ServiceProvider::provides());
        self::assertContains(LogMailer::class, ServiceProvider::provides());
        self::assertContains(NullMailer::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishMailer(): void
    {
        $this->container->setSingleton(MailgunMailer::class, self::createStub(MailgunMailer::class));

        $callback = ServiceProvider::publishers()[MailerContract::class];
        $callback($this->container);

        self::assertInstanceOf(MailgunMailer::class, $this->container->getSingleton(MailerContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishMailgunMailer(): void
    {
        $this->container->setSingleton(Mailgun::class, self::createStub(Mailgun::class));

        $callback = ServiceProvider::publishers()[MailgunMailer::class];
        $callback($this->container);

        self::assertInstanceOf(MailgunMailer::class, $this->container->getSingleton(MailgunMailer::class));
    }

    public function testPublishMailgun(): void
    {
        ServiceProvider::publishMailgunHttpClientConfigurator($this->container);

        $callback = ServiceProvider::publishers()[Mailgun::class];
        $callback($this->container);

        self::assertInstanceOf(Mailgun::class, $this->container->getSingleton(Mailgun::class));
    }

    public function testPublishMailgunHttpClientConfigurator(): void
    {
        $callback = ServiceProvider::publishers()[HttpClientConfigurator::class];
        $callback($this->container);

        self::assertInstanceOf(HttpClientConfigurator::class, $this->container->getSingleton(HttpClientConfigurator::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishPhpMailer(): void
    {
        $this->container->setSingleton(PHPMailerClient::class, self::createStub(PHPMailerClient::class));

        $callback = ServiceProvider::publishers()[PhpMailer::class];
        $callback($this->container);

        self::assertInstanceOf(PhpMailer::class, $this->container->getSingleton(PhpMailer::class));
    }

    public function testPublishPhpMailerClient(): void
    {
        $callback = ServiceProvider::publishers()[PHPMailerClient::class];
        $callback($this->container);

        self::assertInstanceOf(PHPMailerClient::class, $this->container->getSingleton(PHPMailerClient::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogMailer(): void
    {
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        $callback = ServiceProvider::publishers()[LogMailer::class];
        $callback($this->container);

        self::assertInstanceOf(LogMailer::class, $this->container->getSingleton(LogMailer::class));
    }

    public function testPublishNullMailer(): void
    {
        $callback = ServiceProvider::publishers()[NullMailer::class];
        $callback($this->container);

        self::assertInstanceOf(NullMailer::class, $this->container->getSingleton(NullMailer::class));
    }
}
