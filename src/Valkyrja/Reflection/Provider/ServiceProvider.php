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
use Valkyrja\Reflection\Reflector\Contract\Reflector;

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
            Reflector::class => [self::class, 'publishReflection'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Reflector::class,
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
            Reflector::class,
            new \Valkyrja\Reflection\Reflector\Reflector()
        );
    }
}
