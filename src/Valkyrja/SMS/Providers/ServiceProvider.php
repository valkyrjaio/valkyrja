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

namespace Valkyrja\SMS\Providers;

use Nexmo\Client as Nexmo;
use Nexmo\Client\Credentials\Basic;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Logger;
use Valkyrja\SMS\Adapters\LogAdapter;
use Valkyrja\SMS\Adapters\NexmoAdapter;
use Valkyrja\SMS\Adapters\NullAdapter;
use Valkyrja\SMS\Messages\Message;
use Valkyrja\SMS\SMS;

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
            SMS::class          => 'publishSMS',
            LogAdapter::class   => 'publishLogAdapter',
            Message::class      => 'publishMessage',
            Nexmo::class        => 'publishNexmo',
            NexmoAdapter::class => 'publishNexmoAdapter',
            NullAdapter::class  => 'publishNullAdapter',
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
            SMS::class,
            LogAdapter::class,
            Message::class,
            Nexmo::class,
            NexmoAdapter::class,
            NullAdapter::class,
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
     * Publish the SMS service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishSMS(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            SMS::class,
            new \Valkyrja\SMS\Managers\SMS(
                $container,
                $config['sms']
            )
        );
    }

    /**
     * Publish the nexmo message service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNexmo(Container $container): void
    {
        $container->setClosure(
            Nexmo::class,
            static function (array $config): Nexmo {
                return new Nexmo(
                    new Basic($config['username'], $config['password'])
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
     * Publish the nexmo adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNexmoAdapter(Container $container): void
    {
        $container->setClosure(
            NexmoAdapter::class,
            static function (array $config) use ($container): NexmoAdapter {
                return new NexmoAdapter(
                    $container->get(Nexmo::class, [$config])
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
                return (new Message())->setFrom($config['fromName']);
            }
        );
    }
}
