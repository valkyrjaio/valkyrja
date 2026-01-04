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
use Valkyrja\Application\Env\Env;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;
use Valkyrja\Sms\Messenger\LogMessenger;
use Valkyrja\Sms\Messenger\NullMessenger;
use Valkyrja\Sms\Messenger\VonageMessenger;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Credentials\CredentialsInterface;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            MessengerContract::class    => [self::class, 'publishSms'],
            VonageMessenger::class      => [self::class, 'publishVonageSms'],
            Client::class               => [self::class, 'publishVonage'],
            CredentialsInterface::class => [self::class, 'publishVonageCredentials'],
            LogMessenger::class         => [self::class, 'publishLogSms'],
            NullMessenger::class        => [self::class, 'publishNullSms'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            MessengerContract::class,
            VonageMessenger::class,
            Client::class,
            CredentialsInterface::class,
            LogMessenger::class,
            NullMessenger::class,
        ];
    }

    /**
     * Publish the sms service.
     */
    public static function publishSms(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<MessengerContract> $default */
        $default = $env::SMS_DEFAULT_MESSENGER;

        $container->setSingleton(
            MessengerContract::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the vonage sms service.
     */
    public static function publishVonageSms(ContainerContract $container): void
    {
        $container->setSingleton(
            VonageMessenger::class,
            new VonageMessenger(
                $container->getSingleton(Client::class),
            ),
        );
    }

    /**
     * Publish the vonage service.
     */
    public static function publishVonage(ContainerContract $container): void
    {
        $container->setSingleton(
            Client::class,
            new Client(
                credentials: $container->getSingleton(CredentialsInterface::class)
            ),
        );
    }

    /**
     * Publish the vonage credentials service.
     */
    public static function publishVonageCredentials(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string $key */
        $key = $env::SMS_VONAGE_KEY;
        /** @var string $secret */
        $secret = $env::SMS_VONAGE_SECRET;

        $container->setSingleton(
            CredentialsInterface::class,
            new Basic(
                key: $key,
                secret: $secret
            ),
        );
    }

    /**
     * Publish the log sms service.
     */
    public static function publishLogSms(ContainerContract $container): void
    {
        $container->setSingleton(
            LogMessenger::class,
            new LogMessenger(
                $container->getSingleton(LoggerContract::class),
            ),
        );
    }

    /**
     * Publish the null sms service.
     */
    public static function publishNullSms(ContainerContract $container): void
    {
        $container->setSingleton(
            NullMessenger::class,
            new NullMessenger(),
        );
    }
}
