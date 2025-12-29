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
use Valkyrja\Container\Support\Provider;
use Valkyrja\Reflection\Contract\Reflection;

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
            Reflection::class => [self::class, 'publishReflection'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Reflection::class,
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
            Reflection::class,
            new \Valkyrja\Reflection\Reflection()
        );
    }
}
