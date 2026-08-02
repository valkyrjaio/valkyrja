<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Reflection\Provider;

use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Reflection\Reflector\Reflector;

class ReflectionServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the reflection service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishReflection(ContainerContract $container): void
    {
        $container->setSingleton(
            ReflectorContract::class,
            new Reflector()
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            ReflectorContract::class => [self::class, 'publishReflection'],
        ];
    }
}
