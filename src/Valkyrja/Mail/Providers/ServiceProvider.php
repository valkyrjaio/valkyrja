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

namespace Valkyrja\Mail\Providers;

use Mailgun\Mailgun;
use PHPMailer\PHPMailer\PHPMailer;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Logger;
use Valkyrja\Mail\Adapters\LogAdapter;
use Valkyrja\Mail\Adapters\MailgunAdapter;
use Valkyrja\Mail\Adapters\NullAdapter;
use Valkyrja\Mail\Adapters\PHPMailerAdapter;
use Valkyrja\Mail\Drivers\Driver;
use Valkyrja\Mail\Mail;
use Valkyrja\Mail\Messages\Message;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function publishers(): array
    {
        return [
            Mail::class             => 'publishMail',
            Driver::class           => 'publishDefaultDriver',
            LogAdapter::class       => 'publishLogAdapter',
            NullAdapter::class      => 'publishNullAdapter',
            PHPMailer::class        => 'publishPHPMailer',
            PHPMailerAdapter::class => 'publishPHPMailerAdapter',
            Mailgun::class          => 'publishMailgun',
            MailgunAdapter::class   => 'publishMailgunAdapter',
            Message::class          => 'publishMessage',
        ];
    }

    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function provides(): array
    {
        return [
            Mail::class,
            Driver::class,
            LogAdapter::class,
            NullAdapter::class,
            PHPMailer::class,
            PHPMailerAdapter::class,
            Mailgun::class,
            MailgunAdapter::class,
            Message::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
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
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Mail::class,
            new \Valkyrja\Mail\Managers\Mail(
                $container,
                $config['mail']
            )
        );
    }

    /**
     * Publish the default driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDefaultDriver(Container $container): void
    {
        $container->setClosure(
            Driver::class,
            static function (array $config, string $adapter) use ($container): Driver {
                return new Driver(
                    $container->get(
                        $adapter,
                        [
                            $config,
                        ]
                    )
                );
            }
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
        /** @var Logger $logger */
        $logger = $container->getSingleton(Logger::class);

        $container->setClosure(
            LogAdapter::class,
            static function (array $config) use ($logger): LogAdapter {
                return new LogAdapter(
                    $logger->useLogger($config['logger'] ?? null),
                    $config
                );
            }
        );
    }

    /**
     * Publish the null adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNullAdapter(Container $container): void
    {
        $container->setClosure(
            NullAdapter::class,
            static function (array $config): NullAdapter {
                return new NullAdapter(
                    $config
                );
            }
        );
    }

    /**
     * Publish the PHP mailer service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPHPMailer(Container $container): void
    {
        $globalConfig = $container->getSingleton('config');
        $appDebug     = $globalConfig['app']['debug'] ?? null;

        $container->setClosure(
            PHPMailer::class,
            static function (array $config) use ($appDebug): PHPMailer {
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
        );
    }

    /**
     * Publish the PHP Mailer adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPHPMailerAdapter(Container $container): void
    {
        $container->setClosure(
            PHPMailerAdapter::class,
            static function (array $config) use ($container): PHPMailerAdapter {
                return new PHPMailerAdapter(
                    $container->get(PHPMailer::class, [$config])
                );
            }
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
        $container->setClosure(
            Mailgun::class,
            static function (array $config): Mailgun {
                return new Mailgun(
                    $config['apiKey']
                );
            }
        );
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
        $container->setClosure(
            MailgunAdapter::class,
            static function (array $config) use ($container): MailgunAdapter {
                return new MailgunAdapter(
                    $container->get(Mailgun::class, [$config]),
                    $config
                );
            }
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
        $container->setClosure(
            Message::class,
            static function (array $config): Message {
                return (new Message())->setFrom($config['fromEmail'], $config['fromName']);
            }
        );
    }
}
