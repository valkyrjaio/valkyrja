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

use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Sms\Adapter\Contract\Adapter;
use Valkyrja\Sms\Adapter\LogAdapter;
use Valkyrja\Sms\Adapter\NullAdapter;
use Valkyrja\Sms\Adapter\VonageAdapter;
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
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishSMS(Container $container): void
    {
        /** @var array{sms: \Valkyrja\Sms\Config|array<string, mixed>, ...} $config */
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Sms::class,
            new \Valkyrja\Sms\Sms(
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
     * Publish a driver service.
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
     * @param class-string<Driver> $name
     */
    public static function createDriver(Container $container, string $name, Adapter $adapter): Driver
    {
        return new $name(
            adapter: $adapter
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
            [static::class, 'createNullAdapterClass']
        );
    }

    /**
     * @param Container            $container
     * @param array<string, mixed> $config
     */
    public static function createNullAdapterClass(Container $container, array $config): Adapter
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
     * @param array{logger?: string} $config
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
     * Publish a Vonage adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishVonageAdapter(Container $container): void
    {
        $container->setCallable(
            VonageAdapter::class,
            [static::class, 'createVonageAdapter']
        );
    }

    /**
     * @param array{key: string, secret: string} $config
     */
    public static function createVonageAdapter(Container $container, array $config): VonageAdapter
    {
        return new VonageAdapter(
            $container->get(Vonage::class, [$config])
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
        $container->setCallable(
            Vonage::class,
            [static::class, 'createVonageClass']
        );
    }

    /**
     * @param array{key: string, secret: string} $config
     */
    public static function createVonageClass(array $config): Vonage
    {
        return new Vonage(
            new Basic($config['key'], $config['secret'])
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
        $container->setCallable(
            Message::class,
            [static::class, 'createMessageClass']
        );
    }

    /**
     * @param Container               $container
     * @param array{fromName: string} $config
     */
    public static function createMessageClass(Container $container, array $config): Message
    {
        return (new Message())->setFrom($config['fromName']);
    }
}
