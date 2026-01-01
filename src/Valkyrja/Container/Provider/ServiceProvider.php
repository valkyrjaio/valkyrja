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
use Valkyrja\Application\Data\Config;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Container\Collector\AttributeCollector;
use Valkyrja\Container\Collector\Contract\CollectorContract;
use Valkyrja\Container\Contract\ServiceContract;
use Valkyrja\Container\Data\Data;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Throwable\Exception\InvalidArgumentException;

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
            CollectorContract::class => [self::class, 'publishAttributesCollector'],
            Data::class              => [self::class, 'publishData'],
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
            Data::class,
        ];
    }

    /**
     * Publish the attributes service.
     */
    public static function publishAttributesCollector(ContainerContract $container): void
    {
        $container->setSingleton(
            CollectorContract::class,
            new AttributeCollector(
                $container->getSingleton(AttributeCollectorContract::class)
            )
        );
    }

    /**
     * Publish the data service.
     */
    public static function publishData(ContainerContract $container): void
    {
        $config = $container->getSingleton(Config::class);

        $collector = $container->getSingleton(CollectorContract::class);

        foreach ($collector->getServices(...$config->services) as $service) {
            $class = $service->dispatch->getClass();

            if (! is_a($class, ServiceContract::class, true)) {
                throw new InvalidArgumentException("Class for $class must implement " . ServiceContract::class);
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
