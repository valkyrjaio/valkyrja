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
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = MailServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(MailerContract::class, (new MailServiceProvider())->publishers());
        self::assertArrayHasKey(MailgunMailer::class, (new MailServiceProvider())->publishers());
        self::assertArrayHasKey(Mailgun::class, (new MailServiceProvider())->publishers());
        self::assertArrayHasKey(HttpClientConfigurator::class, (new MailServiceProvider())->publishers());
        self::assertArrayHasKey(PhpMailer::class, (new MailServiceProvider())->publishers());
        self::assertArrayHasKey(PHPMailerClient::class, (new MailServiceProvider())->publishers());
        self::assertArrayHasKey(LogMailer::class, (new MailServiceProvider())->publishers());
        self::assertArrayHasKey(NullMailer::class, (new MailServiceProvider())->publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishMailer(): void
    {
        $this->container->setSingleton(MailgunMailer::class, self::createStub(MailgunMailer::class));

        $callback = (new MailServiceProvider())->publishers()[MailerContract::class];
        $callback($this->container);

        self::assertInstanceOf(MailgunMailer::class, $this->container->getSingleton(MailerContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishMailgunMailer(): void
    {
        $this->container->setSingleton(Mailgun::class, self::createStub(Mailgun::class));

        $callback = (new MailServiceProvider())->publishers()[MailgunMailer::class];
        $callback($this->container);

        self::assertInstanceOf(MailgunMailer::class, $this->container->getSingleton(MailgunMailer::class));
    }

    public function testPublishMailgun(): void
    {
        MailServiceProvider::publishMailgunHttpClientConfigurator($this->container);

        $callback = (new MailServiceProvider())->publishers()[Mailgun::class];
        $callback($this->container);

        self::assertInstanceOf(Mailgun::class, $this->container->getSingleton(Mailgun::class));
    }

    public function testPublishMailgunHttpClientConfigurator(): void
    {
        $callback = (new MailServiceProvider())->publishers()[HttpClientConfigurator::class];
        $callback($this->container);

        self::assertInstanceOf(HttpClientConfigurator::class, $this->container->getSingleton(HttpClientConfigurator::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishPhpMailer(): void
    {
        $this->container->setSingleton(PHPMailerClient::class, self::createStub(PHPMailerClient::class));

        $callback = (new MailServiceProvider())->publishers()[PhpMailer::class];
        $callback($this->container);

        self::assertInstanceOf(PhpMailer::class, $this->container->getSingleton(PhpMailer::class));
    }

    public function testPublishPhpMailerClient(): void
    {
        $callback = (new MailServiceProvider())->publishers()[PHPMailerClient::class];
        $callback($this->container);

        self::assertInstanceOf(PHPMailerClient::class, $this->container->getSingleton(PHPMailerClient::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogMailer(): void
    {
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        $callback = (new MailServiceProvider())->publishers()[LogMailer::class];
        $callback($this->container);

        self::assertInstanceOf(LogMailer::class, $this->container->getSingleton(LogMailer::class));
    }

    public function testPublishNullMailer(): void
    {
        $callback = (new MailServiceProvider())->publishers()[NullMailer::class];
        $callback($this->container);

        self::assertInstanceOf(NullMailer::class, $this->container->getSingleton(NullMailer::class));
    }
}
