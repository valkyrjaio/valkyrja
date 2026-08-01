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
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Sms\Data\Contract\SmsConfigContract;
use Valkyrja\Sms\Data\SmsConfig;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;
use Valkyrja\Sms\Messenger\LogMessenger;
use Valkyrja\Sms\Messenger\NullMessenger;
use Valkyrja\Sms\Messenger\VonageMessenger;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Credentials\CredentialsInterface;

class SmsServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the sms config service.
     */
    public static function publishConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof SmsConfigContract) {
            $container->setSingleton(SmsConfigContract::class, $config);

            return;
        }

        $container->setSingleton(
            SmsConfigContract::class,
            new SmsConfig()
        );
    }

    /**
     * Publish the sms service.
     */
    public static function publishSms(ContainerContract $container): void
    {
        $config = $container->getSingleton(SmsConfigContract::class);

        $container->setSingleton(
            MessengerContract::class,
            $container->getSingleton($config->defaultMessenger),
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
        $config = $container->getSingleton(SmsConfigContract::class);

        $container->setSingleton(
            CredentialsInterface::class,
            new Basic(
                key: $config->vonage->key,
                secret: $config->vonage->secret
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

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            SmsConfigContract::class    => [self::class, 'publishConfig'],
            MessengerContract::class    => [self::class, 'publishSms'],
            VonageMessenger::class      => [self::class, 'publishVonageSms'],
            Client::class               => [self::class, 'publishVonage'],
            CredentialsInterface::class => [self::class, 'publishVonageCredentials'],
            LogMessenger::class         => [self::class, 'publishLogSms'],
            NullMessenger::class        => [self::class, 'publishNullSms'],
        ];
    }
}
