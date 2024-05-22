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

namespace Valkyrja\Sms\Providers;

use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Sms\Adapter;
use Valkyrja\Sms\Factories\ContainerFactory;
use Valkyrja\Sms\Factory;
use Valkyrja\Sms\LogAdapter;
use Valkyrja\Sms\Message;
use Valkyrja\Sms\Sms;
use Valkyrja\Sms\VonageAdapter;
use Vonage\Client as Vonage;
use Vonage\Client\Credentials\Basic;

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
            Sms::class           => [self::class, 'publishSMS'],
            Factory::class       => [self::class, 'publishFactory'],
            Adapter::class       => [self::class, 'publishAdapter'],
            LogAdapter::class    => [self::class, 'publishLogAdapter'],
            VonageAdapter::class => [self::class, 'publishVonageAdapter'],
            Vonage::class        => [self::class, 'publishVonage'],
            Message::class       => [self::class, 'publishMessage'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Sms::class,
            Factory::class,
            Adapter::class,
            LogAdapter::class,
            VonageAdapter::class,
            Vonage::class,
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
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Sms::class,
            new \Valkyrja\Sms\Managers\Sms(
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
            /**
             * @param class-string<Adapter> $name
             */
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
            /**
             * @param class-string<LogAdapter> $name
             */
            static function (string $name, array $config) use ($logger): LogAdapter {
                return new $name(
                    $logger->use($config['logger'] ?? null),
                    $config
                );
            }
        );
    }

    /**
     * Publish a Vonage adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishVonageAdapter(Container $container): void
    {
        $container->setClosure(
            VonageAdapter::class,
            /**
             * @param class-string<VonageAdapter> $name
             */
            static function (string $name, array $config) use ($container): VonageAdapter {
                return new $name(
                    $container->get(Vonage::class, [$config])
                );
            }
        );
    }

    /**
     * Publish a Vonage service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishVonage(Container $container): void
    {
        $container->setClosure(
            Vonage::class,
            static function (array $config): Vonage {
                return new Vonage(
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
            /**
             * @param class-string<Message> $name
             */
            static fn (string $name, array $config): Message => (new $name())->setFrom($config['fromName'])
        );
    }
}
