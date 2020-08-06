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
        $config    = $container->getSingleton('config');
        $smsConfig = $config['sms']['adapters']['nexmo'];

        $container->setSingleton(
            Nexmo::class,
            new Nexmo(
                new Basic($smsConfig['username'], $smsConfig['password'])
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
        $container->setSingleton(
            LogAdapter::class,
            new LogAdapter(
                $container->getSingleton(Logger::class)
            )
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
        $container->setSingleton(
            NexmoAdapter::class,
            new NexmoAdapter(
                $container->getSingleton(Nexmo::class)
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
            static function () {
                return new Message();
            }
        );
    }
}
