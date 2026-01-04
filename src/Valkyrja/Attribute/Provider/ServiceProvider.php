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

namespace Valkyrja\Attribute\Provider;

use Override;
use Valkyrja\Attribute\Collector\Collector;
use Valkyrja\Attribute\Collector\Contract\CollectorContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            CollectorContract::class => [self::class, 'publishAttributes'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            CollectorContract::class,
        ];
    }

    /**
     * Publish the attributes service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishAttributes(ContainerContract $container): void
    {
        $container->setSingleton(
            CollectorContract::class,
            new Collector(
                $container->getSingleton(ReflectorContract::class),
            )
        );
    }
}
