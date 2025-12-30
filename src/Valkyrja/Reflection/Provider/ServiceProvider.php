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

namespace Valkyrja\Reflection\Provider;

use Override;
use Valkyrja\Container\Manager\Contract\Container;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Reflection\Reflector\Contract\Reflector as ReflectorContract;
use Valkyrja\Reflection\Reflector\Reflector;

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
            ReflectorContract::class => [self::class, 'publishReflection'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            ReflectorContract::class,
        ];
    }

    /**
     * Publish the reflection service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishReflection(Container $container): void
    {
        $container->setSingleton(
            ReflectorContract::class,
            new Reflector()
        );
    }
}
