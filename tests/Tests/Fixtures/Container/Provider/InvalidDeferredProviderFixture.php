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
 * Class InvalidDeferredProviderFixture.
 */
final class InvalidDeferredProviderFixture implements ServiceProviderContract
{
    /**
     * The publisher names a method that does not exist, which is exactly what the
     * container's registration guard has to reject.
     *
     * @return array<array-key, mixed>
     */
    private static function brokenPublishers(): array
    {
        return [
            ProvidedSecondaryFixture::class => [self::class, 'publishMethodNonExistent'],
        ];
    }

    #[Override]
    public function publishers(): array
    {
        /** @var array<class-string, callable(ContainerContract): void> $publishers */
        $publishers = self::brokenPublishers();

        return $publishers;
    }
}
