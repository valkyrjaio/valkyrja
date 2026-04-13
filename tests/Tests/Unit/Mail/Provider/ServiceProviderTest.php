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
use Valkyrja\Mail\Provider\MailServiceProvider;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = MailServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(MailerContract::class, MailServiceProvider::publishers());
        self::assertArrayHasKey(MailgunMailer::class, MailServiceProvider::publishers());
        self::assertArrayHasKey(Mailgun::class, MailServiceProvider::publishers());
        self::assertArrayHasKey(HttpClientConfigurator::class, MailServiceProvider::publishers());
        self::assertArrayHasKey(PhpMailer::class, MailServiceProvider::publishers());
        self::assertArrayHasKey(PHPMailerClient::class, MailServiceProvider::publishers());
        self::assertArrayHasKey(LogMailer::class, MailServiceProvider::publishers());
        self::assertArrayHasKey(NullMailer::class, MailServiceProvider::publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishMailer(): void
    {
        $this->container->setSingleton(MailgunMailer::class, self::createStub(MailgunMailer::class));

        $callback = MailServiceProvider::publishers()[MailerContract::class];
        $callback($this->container);

        self::assertInstanceOf(MailgunMailer::class, $this->container->getSingleton(MailerContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishMailgunMailer(): void
    {
        $this->container->setSingleton(Mailgun::class, self::createStub(Mailgun::class));

        $callback = MailServiceProvider::publishers()[MailgunMailer::class];
        $callback($this->container);

        self::assertInstanceOf(MailgunMailer::class, $this->container->getSingleton(MailgunMailer::class));
    }

    public function testPublishMailgun(): void
    {
        MailServiceProvider::publishMailgunHttpClientConfigurator($this->container);

        $callback = MailServiceProvider::publishers()[Mailgun::class];
        $callback($this->container);

        self::assertInstanceOf(Mailgun::class, $this->container->getSingleton(Mailgun::class));
    }

    public function testPublishMailgunHttpClientConfigurator(): void
    {
        $callback = MailServiceProvider::publishers()[HttpClientConfigurator::class];
        $callback($this->container);

        self::assertInstanceOf(HttpClientConfigurator::class, $this->container->getSingleton(HttpClientConfigurator::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishPhpMailer(): void
    {
        $this->container->setSingleton(PHPMailerClient::class, self::createStub(PHPMailerClient::class));

        $callback = MailServiceProvider::publishers()[PhpMailer::class];
        $callback($this->container);

        self::assertInstanceOf(PhpMailer::class, $this->container->getSingleton(PhpMailer::class));
    }

    public function testPublishPhpMailerClient(): void
    {
        $callback = MailServiceProvider::publishers()[PHPMailerClient::class];
        $callback($this->container);

        self::assertInstanceOf(PHPMailerClient::class, $this->container->getSingleton(PHPMailerClient::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogMailer(): void
    {
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        $callback = MailServiceProvider::publishers()[LogMailer::class];
        $callback($this->container);

        self::assertInstanceOf(LogMailer::class, $this->container->getSingleton(LogMailer::class));
    }

    public function testPublishNullMailer(): void
    {
        $callback = MailServiceProvider::publishers()[NullMailer::class];
        $callback($this->container);

        self::assertInstanceOf(NullMailer::class, $this->container->getSingleton(NullMailer::class));
    }
}
