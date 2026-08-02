<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Attribute\Provider;

use Override;
use Valkyrja\Attribute\Collector\Collector;
use Valkyrja\Attribute\Collector\Contract\CollectorContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;

class AttributeServiceProvider implements ServiceProviderContract
{
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

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            CollectorContract::class => [self::class, 'publishAttributes'],
        ];
    }
}
