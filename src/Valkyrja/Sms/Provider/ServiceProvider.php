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

namespace Valkyrja\Sms\Provider;

use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Sms\Adapter\Contract\Adapter;
use Valkyrja\Sms\Adapter\LogAdapter;
use Valkyrja\Sms\Adapter\NullAdapter;
use Valkyrja\Sms\Adapter\VonageAdapter;
use Valkyrja\Sms\Config;
use Valkyrja\Sms\Config\LogConfiguration;
use Valkyrja\Sms\Config\MessageConfiguration;
use Valkyrja\Sms\Config\NullConfiguration;
use Valkyrja\Sms\Config\VonageConfiguration;
use Valkyrja\Sms\Contract\Sms;
use Valkyrja\Sms\Driver\Driver;
use Valkyrja\Sms\Factory\ContainerFactory;
use Valkyrja\Sms\Factory\Contract\Factory;
use Valkyrja\Sms\Message\Message;
use Vonage\Client as Vonage;
use Vonage\Client\Credentials\Basic;

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
            Sms::class           => [self::class, 'publishSMS'],
            Factory::class       => [self::class, 'publishFactory'],
            Driver::class        => [self::class, 'publishDriver'],
            NullAdapter::class   => [self::class, 'publishNullAdapter'],
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
            Driver::class,
            NullAdapter::class,
            LogAdapter::class,
            VonageAdapter::class,
            Vonage::class,
            Message::class,
        ];
    }

    /**
     * Publish the SMS service.
     */
    public static function publishSMS(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Sms::class,
            new \Valkyrja\Sms\Sms(
                $container->getSingleton(Factory::class),
                $config
            )
        );
    }

    /**
     * Publish the factory service.
     */
    public static function publishFactory(Container $container): void
    {
        $container->setSingleton(
            Factory::class,
            new ContainerFactory($container),
        );
    }

    /**
     * Publish a driver service.
     */
    public static function publishDriver(Container $container): void
    {
        $container->setCallable(
            Driver::class,
            [self::class, 'createDriver']
        );
    }

    /**
     * Create a driver.
     *
     * @param class-string<Driver> $name The driver name
     */
    public static function createDriver(Container $container, string $name, Adapter $adapter): Driver
    {
        return new $name(
            adapter: $adapter
        );
    }

    /**
     * Publish an adapter service.
     */
    public static function publishNullAdapter(Container $container): void
    {
        $container->setCallable(
            NullAdapter::class,
            [self::class, 'createNullAdapterClass']
        );
    }

    /**
     * Create a null adapter.
     */
    public static function createNullAdapterClass(Container $container, NullConfiguration $config): Adapter
    {
        return new NullAdapter(
            $config
        );
    }

    /**
     * Publish the log adapter service.
     */
    public static function publishLogAdapter(Container $container): void
    {
        $container->setCallable(
            LogAdapter::class,
            [self::class, 'createLogAdapter']
        );
    }

    /**
     * Create a log adapter.
     */
    public static function createLogAdapter(Container $container, LogConfiguration $config): LogAdapter
    {
        return new LogAdapter(
            $container->getSingleton(Logger::class),
            $config
        );
    }

    /**
     * Publish a Vonage adapter service.
     */
    public static function publishVonageAdapter(Container $container): void
    {
        $container->setCallable(
            VonageAdapter::class,
            [self::class, 'createVonageAdapter']
        );
    }

    /**
     * Create a vonage adapter.
     */
    public static function createVonageAdapter(Container $container, VonageConfiguration $config): VonageAdapter
    {
        return new VonageAdapter(
            $container->get(Vonage::class, [$config])
        );
    }

    /**
     * Publish a Vonage service.
     */
    public static function publishVonage(Container $container): void
    {
        $container->setCallable(
            Vonage::class,
            [self::class, 'createVonageClass']
        );
    }

    /**
     * Create the vonage class.
     */
    public static function createVonageClass(VonageConfiguration $config): Vonage
    {
        return new Vonage(
            new Basic($config->key, $config->secret)
        );
    }

    /**
     * Publish a message service.
     */
    public static function publishMessage(Container $container): void
    {
        $container->setCallable(
            Message::class,
            [self::class, 'createMessageClass']
        );
    }

    /**
     * Create a message.
     */
    public static function createMessageClass(Container $container, MessageConfiguration $config): Message
    {
        return (new Message())->setFrom($config->from);
    }
}
