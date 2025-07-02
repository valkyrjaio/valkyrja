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

namespace Valkyrja\Mail\Provider;

use GuzzleHttp\Client;
use Mailgun\HttpClient\HttpClientConfigurator;
use Mailgun\Mailgun;
use PHPMailer\PHPMailer\PHPMailer as PHPMailerClient;
use Valkyrja\Application\Env;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Mail\Contract\Mailer;
use Valkyrja\Mail\LogMailer;
use Valkyrja\Mail\MailgunMailer;
use Valkyrja\Mail\NullMailer;
use Valkyrja\Mail\PhpMailer;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Mailer::class                 => [self::class, 'publishMailer'],
            MailgunMailer::class          => [self::class, 'publishMailgunMailer'],
            Mailgun::class                => [self::class, 'publishMailgun'],
            HttpClientConfigurator::class => [self::class, 'publishMailgunHttpClientConfigurator'],
            PhpMailer::class              => [self::class, 'publishPhpMailer'],
            PHPMailerClient::class        => [self::class, 'publishPhpMailerClient'],
            LogMailer::class              => [self::class, 'publishLogMailer'],
            NullMailer::class             => [self::class, 'publishNullMailer'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Mailer::class,
            MailgunMailer::class,
            Mailgun::class,
            HttpClientConfigurator::class,
            PhpMailer::class,
            PHPMailerClient::class,
            LogMailer::class,
            NullMailer::class,
        ];
    }

    /**
     * Publish the mailer service.
     */
    public static function publishMailer(Container $container): void
    {
        $container->setSingleton(
            Mailer::class,
            $container->getSingleton(MailgunMailer::class),
        );
    }

    /**
     * Publish the mailgun mailer service.
     */
    public static function publishMailgunMailer(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string $domain */
        $domain = $env::MAIL_MAILGUN_DOMAIN;

        $container->setSingleton(
            MailgunMailer::class,
            new MailgunMailer(
                $container->getSingleton(Mailgun::class),
                $domain
            )
        );
    }

    /**
     * Publish the mailgun service.
     */
    public static function publishMailgun(Container $container): void
    {
        $container->setSingleton(
            Mailer::class,
            new Mailgun(
                $container->getSingleton(HttpClientConfigurator::class),
            )
        );
    }

    /**
     * Publish the mailgun service.
     */
    public static function publishMailgunHttpClientConfigurator(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string $apiKey */
        $apiKey = $env::MAIL_MAILGUN_API_KEY;

        $container->setSingleton(
            HttpClientConfigurator::class,
            (new HttpClientConfigurator())
                ->setApiKey($apiKey)
                ->setHttpClient(new Client())
        );
    }

    /**
     * Publish the PHPMailer mailer service.
     */
    public static function publishPhpMailer(Container $container): void
    {
        $container->setSingleton(
            PhpMailer::class,
            new PhpMailer(
                $container->getSingleton(PHPMailerClient::class),
            ),
        );
    }

    /**
     * Publish the PHPMailer client service.
     */
    public static function publishPhpMailerClient(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var bool $debugMode */
        $debugMode = $env::APP_DEBUG_MODE;
        /** @var string $host */
        $host = $env::MAIL_PHP_MAILER_HOST;
        /** @var int $port */
        $port = $env::MAIL_PHP_MAILER_PORT;
        /** @var string $username */
        $username = $env::MAIL_PHP_MAILER_USERNAME;
        /** @var string $password */
        $password = $env::MAIL_PHP_MAILER_PASSWORD;
        /** @var string $encryption */
        $encryption = $env::MAIL_PHP_MAILER_ENCRYPTION;

        // Create a new instance of the PHPMailer class
        $mailer = new PHPMailerClient(true);

        // Enable verbose debug output
        $mailer->SMTPDebug = $debugMode ? 2 : 0;
        // Set mailer to use SMTP
        $mailer->isSMTP();
        // Specify main and backup SMTP servers
        $mailer->Host = $host;
        // SMTP Port
        $mailer->Port = $port;
        // Enable SMTP authentication
        $mailer->SMTPAuth = true;
        // SMTP username
        $mailer->Username = $username;
        // SMTP password
        $mailer->Password = $password;
        // Enable TLS encryption, `ssl` also accepted
        $mailer->SMTPSecure = $encryption;

        $container->setSingleton(
            PHPMailerClient::class,
            $mailer
        );
    }

    /**
     * Publish the log mailer service.
     */
    public static function publishLogMailer(Container $container): void
    {
        $container->setSingleton(
            LogMailer::class,
            new LogMailer(
                $container->getSingleton(Logger::class),
            ),
        );
    }

    /**
     * Publish the null mailer service.
     */
    public static function publishNullMailer(Container $container): void
    {
        $container->setSingleton(
            NullMailer::class,
            new NullMailer(),
        );
    }
}
