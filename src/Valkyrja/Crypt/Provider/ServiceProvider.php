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

use Valkyrja\Application\Env;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Crypt\NullCrypt;
use Valkyrja\Crypt\SodiumCrypt;

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
            Crypt::class       => [self::class, 'publishCrypt'],
            SodiumCrypt::class => [self::class, 'publishSodiumCrypt'],
            NullCrypt::class   => [self::class, 'publishNullCrypt'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Crypt::class,
            SodiumCrypt::class,
            NullCrypt::class,
        ];
    }

    /**
     * Publish the crypt service.
     */
    public static function publishCrypt(Container $container): void
    {
        $container->setSingleton(
            Crypt::class,
            $container->getSingleton(SodiumCrypt::class),
        );
    }

    /**
     * Publish the sodium crypt service.
     */
    public static function publishSodiumCrypt(Container $container): void
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
    public static function publishNullCrypt(Container $container): void
    {
        $container->setSingleton(
            NullCrypt::class,
            new NullCrypt()
        );
    }
}
