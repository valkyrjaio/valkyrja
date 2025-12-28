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

use Override;
use Valkyrja\Application\Env;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Logger\Contract\Logger;
use Valkyrja\Sms\Messenger\Contract\Messenger;
use Valkyrja\Sms\Messenger\LogMessenger;
use Valkyrja\Sms\Messenger\NullMessenger;
use Valkyrja\Sms\Messenger\VonageMessenger;
use Vonage\Client as Vonage;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Credentials\CredentialsInterface as VonageCredentials;

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
    #[Override]
    public static function publishers(): array
    {
        return [
            Messenger::class         => [self::class, 'publishSms'],
            VonageMessenger::class   => [self::class, 'publishVonageSms'],
            Vonage::class            => [self::class, 'publishVonage'],
            VonageCredentials::class => [self::class, 'publishVonageCredentials'],
            LogMessenger::class      => [self::class, 'publishLogSms'],
            NullMessenger::class     => [self::class, 'publishNullSms'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Messenger::class,
            VonageMessenger::class,
            Vonage::class,
            VonageCredentials::class,
            LogMessenger::class,
            NullMessenger::class,
        ];
    }

    /**
     * Publish the sms service.
     */
    public static function publishSms(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<Messenger> $default */
        $default = $env::SMS_DEFAULT_MESSENGER;

        $container->setSingleton(
            Messenger::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the vonage sms service.
     */
    public static function publishVonageSms(Container $container): void
    {
        $container->setSingleton(
            VonageMessenger::class,
            new VonageMessenger(
                $container->getSingleton(Vonage::class),
            ),
        );
    }

    /**
     * Publish the vonage service.
     */
    public static function publishVonage(Container $container): void
    {
        $container->setSingleton(
            Vonage::class,
            new Vonage(
                credentials: $container->getSingleton(VonageCredentials::class)
            ),
        );
    }

    /**
     * Publish the vonage credentials service.
     */
    public static function publishVonageCredentials(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string $key */
        $key = $env::SMS_VONAGE_KEY;
        /** @var string $secret */
        $secret = $env::SMS_VONAGE_SECRET;

        $container->setSingleton(
            VonageCredentials::class,
            new Basic(
                key: $key,
                secret: $secret
            ),
        );
    }

    /**
     * Publish the log sms service.
     */
    public static function publishLogSms(Container $container): void
    {
        $container->setSingleton(
            LogMessenger::class,
            new LogMessenger(
                $container->getSingleton(Logger::class),
            ),
        );
    }

    /**
     * Publish the null sms service.
     */
    public static function publishNullSms(Container $container): void
    {
        $container->setSingleton(
            NullMessenger::class,
            new NullMessenger(),
        );
    }
}
