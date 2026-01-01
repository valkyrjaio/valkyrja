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
use Valkyrja\Application\Env\Env;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Crypt\Manager\NullCrypt;
use Valkyrja\Crypt\Manager\SodiumCrypt;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            CryptContract::class => [self::class, 'publishCrypt'],
            SodiumCrypt::class   => [self::class, 'publishSodiumCrypt'],
            NullCrypt::class     => [self::class, 'publishNullCrypt'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            CryptContract::class,
            SodiumCrypt::class,
            NullCrypt::class,
        ];
    }

    /**
     * Publish the crypt service.
     */
    public static function publishCrypt(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<CryptContract> $default */
        $default = $env::CRYPT_DEFAULT;

        $container->setSingleton(
            CryptContract::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the sodium crypt service.
     */
    public static function publishSodiumCrypt(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string $key */
        $key = $env::APP_KEY;

        $container->setSingleton(
            SodiumCrypt::class,
            new SodiumCrypt(
                key: $key
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
}
