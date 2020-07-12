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
use Valkyrja\SMS\Messages\NexmoMessage;
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
            NexmoMessage::class => 'publishXenmoMessage',
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
            NexmoMessage::class,
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
                (array) $config['sms']
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
    public static function publishXenmoMessage(Container $container): void
    {
        $config    = $container->getSingleton('config');
        $smsConfig = $config['sms'];

        $nexmo = new Nexmo(
            new Basic($smsConfig['username'], $smsConfig['password'])
        );

        $container->setSingleton(
            NexmoMessage::class,
            new NexmoMessage(
                $nexmo
            )
        );
    }
}
