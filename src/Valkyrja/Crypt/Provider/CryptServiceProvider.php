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

namespace Valkyrja\Crypt\Provider;

use Override;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Crypt\Data\Contract\CryptConfigContract;
use Valkyrja\Crypt\Data\CryptConfig;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Crypt\Manager\NullCrypt;
use Valkyrja\Crypt\Manager\SodiumCrypt;

class CryptServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the crypt config service.
     */
    public static function publishConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof CryptConfigContract) {
            $container->setSingleton(CryptConfigContract::class, $config);

            return;
        }

        $container->setSingleton(
            CryptConfigContract::class,
            new CryptConfig()
        );
    }

    /**
     * Publish the crypt service.
     */
    public static function publishCrypt(ContainerContract $container): void
    {
        $config = $container->getSingleton(CryptConfigContract::class);

        $container->setSingleton(
            CryptContract::class,
            $container->getSingleton($config->defaultCrypt),
        );
    }

    /**
     * Publish the sodium crypt service.
     */
    public static function publishSodiumCrypt(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        $container->setSingleton(
            SodiumCrypt::class,
            new SodiumCrypt(
                key: $config->key
            )
        );
    }

    /**
     * Publish the null crypt service.
     */
    public static function publishNullCrypt(ContainerContract $container): void
    {
        $container->setSingleton(
            NullCrypt::class,
            new NullCrypt()
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            CryptConfigContract::class => [self::class, 'publishConfig'],
            CryptContract::class       => [self::class, 'publishCrypt'],
            SodiumCrypt::class         => [self::class, 'publishSodiumCrypt'],
            NullCrypt::class           => [self::class, 'publishNullCrypt'],
        ];
    }
}
