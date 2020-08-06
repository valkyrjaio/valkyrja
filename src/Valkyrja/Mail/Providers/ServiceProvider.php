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
     * Publish the log adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLogAdapter(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            LogAdapter::class,
            new LogAdapter(
                $container->getSingleton(Logger::class),
                $config['mail']['adapters']['log']
            )
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
        $container->setSingleton(
            NullAdapter::class,
            new NullAdapter()
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
        $config     = $container->getSingleton('config');
        $mailConfig = $config['mail']['adapters']['phpMailer'];

        // Create a new instance of the PHPMailer class
        $PHPMailer = new PHPMailer(true);

        // Enable verbose debug output
        $PHPMailer->SMTPDebug = $config['app']['debug'] ? 2 : 0;
        // Set mailer to use SMTP
        $PHPMailer->isSMTP();
        // Specify main and backup SMTP servers
        $PHPMailer->Host = $mailConfig['host'];
        // SMTP Port
        $PHPMailer->Port = $mailConfig['port'];
        // Enable SMTP authentication
        $PHPMailer->SMTPAuth = true;
        // SMTP username
        $PHPMailer->Username = $mailConfig['username'];
        // SMTP password
        $PHPMailer->Password = $mailConfig['password'];
        // Enable TLS encryption, `ssl` also accepted
        $PHPMailer->SMTPSecure = $mailConfig['encryption'];

        $container->setSingleton(
            PHPMailer::class,
            $PHPMailer
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
        $container->setSingleton(
            PHPMailerAdapter::class,
            new PHPMailerAdapter(
                $container->getSingleton(PHPMailer::class)
            )
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
        $config     = $container->getSingleton('config');
        $mailConfig = $config['mail']['adapters']['mailgun'];

        $container->setSingleton(
            Mailgun::class,
            new Mailgun($mailConfig['apiKey'])
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
        $config     = $container->getSingleton('config');
        $mailConfig = $config['mail']['adapters']['mailgun'];

        $container->setSingleton(
            MailgunAdapter::class,
            new MailgunAdapter(
                $container->getSingleton(PHPMailer::class),
                $mailConfig
            )
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
        $config     = $container->getSingleton('config');
        $mailConfig = $config['mail'];

        $container->setClosure(
            Message::class,
            static function () use ($mailConfig): Message {
                return (new Message())->setFrom($mailConfig['fromEmail'], $mailConfig['fromName']);
            }
        );
    }
}
