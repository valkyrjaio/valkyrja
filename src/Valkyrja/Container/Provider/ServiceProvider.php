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

namespace Valkyrja\Container\Provider;

use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Container\Attribute\Contract\Collector;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;

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
            Collector::class => [self::class, 'publishAttributesCollector'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Collector::class,
        ];
    }

    /**
     * Publish the attributes service.
     */
    public static function publishAttributesCollector(Container $container): void
    {
        $container->setSingleton(
            Collector::class,
            new \Valkyrja\Container\Attribute\Collector(
                $container->getSingleton(Attributes::class)
            )
        );
    }
}
