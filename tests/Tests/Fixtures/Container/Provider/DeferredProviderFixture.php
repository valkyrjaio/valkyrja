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
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;

/**
 * Class DeferredProviderFixture.
 */
final class DeferredProviderFixture implements ServiceProviderContract
{
    public static bool $publishCalled = false;

    public static bool $publishSecondaryCalled = false;

    public static function publish(object $providerAware): void
    {
        self::$publishCalled = true;
    }

    public static function publishSecondary(object $providerAware): void
    {
        self::$publishSecondaryCalled = true;
    }

    #[Override]
    public function publishers(): array
    {
        return [
            ProvidedFixture::class          => [self::class, 'publish'],
            ProvidedSecondaryFixture::class => [self::class, 'publishSecondary'],
        ];
    }
}
