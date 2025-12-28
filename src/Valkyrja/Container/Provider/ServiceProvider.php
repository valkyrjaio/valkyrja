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

use Override;
use Valkyrja\Application\Config;
use Valkyrja\Attribute\Collector\Contract\Collector as AttributeCollectorContract;
use Valkyrja\Container\Collector\AttributeCollector;
use Valkyrja\Container\Collector\Contract\Collector;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Contract\Service;
use Valkyrja\Container\Data;
use Valkyrja\Container\Exception\InvalidArgumentException;
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
    #[Override]
    public static function publishers(): array
    {
        return [
            Collector::class => [self::class, 'publishAttributesCollector'],
            Data::class      => [self::class, 'publishData'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Collector::class,
            Data::class,
        ];
    }

    /**
     * Publish the attributes service.
     */
    public static function publishAttributesCollector(Container $container): void
    {
        $container->setSingleton(
            Collector::class,
            new AttributeCollector(
                $container->getSingleton(AttributeCollectorContract::class)
            )
        );
    }

    /**
     * Publish the data service.
     */
    public static function publishData(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        $collector = $container->getSingleton(Collector::class);

        foreach ($collector->getServices(...$config->services) as $service) {
            $class = $service->dispatch->getClass();

            if (! is_a($class, Service::class, true)) {
                throw new InvalidArgumentException("Class for $class must implement " . Service::class);
            }

            if ($service->isSingleton) {
                $container->bindSingleton($service->serviceId, $class);

                continue;
            }

            $container->bind($service->serviceId, $class);
        }

        foreach ($collector->getAliases(...$config->aliases) as $service) {
            $container->bindAlias($service->dispatch->getClass(), $service->serviceId);
        }

        $container->setSingleton(
            Data::class,
            new Data(
                providers: $config->providers
            )
        );
    }
}
