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

use PHPMailer\PHPMailer\PHPMailer;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Mail\Mail;
use Valkyrja\Mail\Messages\PHPMailerMessage;

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
            PHPMailerMessage::class => 'publishMailerMessage',
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
            PHPMailerMessage::class,
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
                (array) $config['mail']
            )
        );
    }

    /**
     * Publish the PHP mailer message service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishMailerMessage(Container $container): void
    {
        $config     = $container->getSingleton('config');
        $mailConfig = $config['mail'];

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
            PHPMailerMessage::class,
            new PHPMailerMessage(
                $PHPMailer
            )
        );
    }
}
