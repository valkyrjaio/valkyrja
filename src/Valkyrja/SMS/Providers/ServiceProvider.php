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
use Valkyrja\SMS\Adapter;
use Valkyrja\SMS\Factories\ContainerFactory;
use Valkyrja\SMS\Factory;
use Valkyrja\SMS\LogAdapter;
use Valkyrja\SMS\Message;
use Valkyrja\SMS\NexmoAdapter;
use Valkyrja\SMS\SMS;

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
            SMS::class          => 'publishSMS',
            Factory::class      => 'publishFactory',
            Adapter::class      => 'publishAdapter',
            LogAdapter::class   => 'publishLogAdapter',
            NexmoAdapter::class => 'publishNexmoAdapter',
            Nexmo::class        => 'publishNexmo',
            Message::class      => 'publishMessage',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            SMS::class,
            Factory::class,
            Adapter::class,
            LogAdapter::class,
            NexmoAdapter::class,
            Nexmo::class,
            Message::class,
        ];
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
                $container->getSingleton(Factory::class),
                $config['sms']
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
     * Publish an adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAdapter(Container $container): void
    {
        $container->setClosure(
            Adapter::class,
            static function (string $name, array $config): Adapter {
                return new $name(
                    $config
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
        $logger = $container->getSingleton(Logger::class);

        $container->setClosure(
            LogAdapter::class,
            static function (string $name, array $config) use ($logger): LogAdapter {
                return new $name(
                    $logger->use($config['logger'] ?? null),
                    $config
                );
            }
        );
    }

    /**
     * Publish a nexmo adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNexmoAdapter(Container $container): void
    {
        $container->setClosure(
            NexmoAdapter::class,
            static function (string $name, array $config) use ($container): NexmoAdapter {
                return new $name(
                    $container->get(Nexmo::class, [$config])
                );
            }
        );
    }

    /**
     * Publish a nexmo service.
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
     * Publish a message service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishMessage(Container $container): void
    {
        $container->setClosure(
            Message::class,
            static function (string $name, array $config): Message {
                return (new $name())->setFrom($config['fromName']);
            }
        );
    }
}
