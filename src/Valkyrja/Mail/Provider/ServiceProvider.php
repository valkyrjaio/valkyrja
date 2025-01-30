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
use PHPMailer\PHPMailer\PHPMailer;
use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Mail\Adapter\Contract\Adapter;
use Valkyrja\Mail\Adapter\LogAdapter;
use Valkyrja\Mail\Adapter\MailgunAdapter;
use Valkyrja\Mail\Adapter\NullAdapter;
use Valkyrja\Mail\Adapter\PHPMailerAdapter;
use Valkyrja\Mail\Contract\Mail;
use Valkyrja\Mail\Driver\Driver;
use Valkyrja\Mail\Factory\ContainerFactory;
use Valkyrja\Mail\Factory\Contract\Factory;
use Valkyrja\Mail\Message\Message;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Mail::class             => [self::class, 'publishMail'],
            Factory::class          => [self::class, 'publishFactory'],
            Driver::class           => [self::class, 'publishDriver'],
            NullAdapter::class      => [self::class, 'publishNullAdapter'],
            LogAdapter::class       => [self::class, 'publishLogAdapter'],
            PHPMailerAdapter::class => [self::class, 'publishPHPMailerAdapter'],
            MailgunAdapter::class   => [self::class, 'publishMailgunAdapter'],
            PHPMailer::class        => [self::class, 'publishPHPMailer'],
            Mailgun::class          => [self::class, 'publishMailgun'],
            Message::class          => [self::class, 'publishMessage'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Mail::class,
            Factory::class,
            Driver::class,
            NullAdapter::class,
            LogAdapter::class,
            PHPMailerAdapter::class,
            MailgunAdapter::class,
            PHPMailer::class,
            Mailgun::class,
            Message::class,
        ];
    }

    /**
     * Publish the mail service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishMail(Container $container): void
    {
        /** @var array{mail: \Valkyrja\Mail\Config|array<string, mixed>, ...} $config */
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Mail::class,
            new \Valkyrja\Mail\Mail(
                $container->getSingleton(Factory::class),
                $config['mail']
            )
        );
    }

    /**
     * Publish the factory service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFactory(Container $container): void
    {
        $container->setSingleton(
            Factory::class,
            new ContainerFactory($container),
        );
    }

    /**
     * Publish the default driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDriver(Container $container): void
    {
        $container->setCallable(
            Driver::class,
            [static::class, 'createDriver']
        );
    }

    /**
     * Create a driver.
     *
     * @param Container $container
     * @param Adapter   $adapter
     *
     * @return Driver
     */
    public static function createDriver(Container $container, Adapter $adapter): Driver
    {
        return new Driver(
            $adapter
        );
    }

    /**
     * Publish an adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNullAdapter(Container $container): void
    {
        $container->setCallable(
            NullAdapter::class,
            [static::class, 'createNullAdapter']
        );
    }

    /**
     * Create a null adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return NullAdapter
     */
    public static function createNullAdapter(Container $container, array $config): NullAdapter
    {
        return new NullAdapter(
            $config
        );
    }

    /**
     * Publish the log adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLogAdapter(Container $container): void
    {
        $container->setCallable(
            LogAdapter::class,
            [static::class, 'createLogAdapter']
        );
    }

    /**
     * Create a log adapter.
     *
     * @param Container              $container
     * @param array{logger?: string} $config
     *
     * @return LogAdapter
     */
    public static function createLogAdapter(Container $container, array $config): LogAdapter
    {
        $logger = $container->getSingleton(Logger::class);

        return new LogAdapter(
            $logger->use($config['logger'] ?? null),
            $config
        );
    }

    /**
     * Publish the PHPMailer adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPHPMailerAdapter(Container $container): void
    {
        $container->setCallable(
            PHPMailerAdapter::class,
            [static::class, 'createPHPMailerAdapter']
        );
    }

    /**
     * Create a PHPMailer adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return PHPMailerAdapter
     */
    public static function createPHPMailerAdapter(Container $container, array $config): PHPMailerAdapter
    {
        return new PHPMailerAdapter(
            $container->get(PHPMailer::class, [$config])
        );
    }

    /**
     * Publish the PHP Mailer service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPHPMailer(Container $container): void
    {
        $container->setCallable(
            PHPMailer::class,
            [static::class, 'createPHPMailer']
        );
    }

    /**
     * Create a PHPMailer service.
     *
     * @param Container                                                                              $container
     * @param array{host: string, port: int, username: string, password: string, encryption: string} $config
     *
     * @return PHPMailer
     */
    public static function createPHPMailer(Container $container, array $config): PHPMailer
    {
        /** @var array{app?: array{debug?: bool, ...}} $globalConfig */
        $globalConfig = $container->getSingleton(Config::class);
        $appDebug     = $globalConfig['app']['debug'] ?? false;

        // Create a new instance of the PHPMailer class
        $mailer = new PHPMailer(true);

        // Enable verbose debug output
        $mailer->SMTPDebug = $appDebug ? 2 : 0;
        // Set mailer to use SMTP
        $mailer->isSMTP();
        // Specify main and backup SMTP servers
        $mailer->Host = $config['host'];
        // SMTP Port
        $mailer->Port = $config['port'];
        // Enable SMTP authentication
        $mailer->SMTPAuth = true;
        // SMTP username
        $mailer->Username = $config['username'];
        // SMTP password
        $mailer->Password = $config['password'];
        // Enable TLS encryption, `ssl` also accepted
        $mailer->SMTPSecure = $config['encryption'];

        return $mailer;
    }

    /**
     * Publish the Mailgun adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishMailgunAdapter(Container $container): void
    {
        $container->setCallable(
            MailgunAdapter::class,
            [static::class, 'createMailgunAdapter']
        );
    }

    /**
     * Create a mailgun adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return MailgunAdapter
     */
    public static function createMailgunAdapter(Container $container, array $config): MailgunAdapter
    {
        return new MailgunAdapter(
            $container->get(Mailgun::class, [$config]),
            $config
        );
    }

    /**
     * Publish the Mailgun service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishMailgun(Container $container): void
    {
        $container->setCallable(
            Mailgun::class,
            [static::class, 'createMailgun']
        );
    }

    /**
     * Create a mailgun service.
     *
     * @param array{apiKey: string} $config
     *
     * @return Mailgun
     */
    public static function createMailgun(array $config): Mailgun
    {
        $httpClientConfigurator = (new HttpClientConfigurator())
            ->setApiKey($config['apiKey'])
            ->setHttpClient(new Client());

        return new Mailgun(
            $httpClientConfigurator
        );
    }

    /**
     * Publish the message service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishMessage(Container $container): void
    {
        $container->setCallable(
            Message::class,
            [static::class, 'createMessage']
        );
    }

    /**
     * Create a message.
     *
     * @param Container                                    $container
     * @param array{fromAddress: string, fromName: string} $config
     *
     * @return Message
     */
    public static function createMessage(Container $container, array $config): Message
    {
        return (new Message())->setFrom(
            $config['fromAddress'],
            $config['fromName']
        );
    }
}
