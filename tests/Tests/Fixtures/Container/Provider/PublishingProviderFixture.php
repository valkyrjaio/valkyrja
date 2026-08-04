<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Container\Provider;

use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;

/**
 * A service provider that publishes one singleton into the container.
 */
final class PublishingProviderFixture implements ServiceProviderContract
{
    public static function publishProvided(ContainerContract $container): void
    {
        $container->setSingleton(ProvidedFixture::class, new ProvidedFixture());
    }

    #[Override]
    public function publishers(): array
    {
        return [
            ProvidedFixture::class => [self::class, 'publishProvided'],
        ];
    }
}
