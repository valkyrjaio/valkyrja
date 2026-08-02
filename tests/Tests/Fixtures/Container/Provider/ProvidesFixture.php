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
 * Testable Provider/Provides Trait class.
 */
final class ProvidesFixture implements ServiceProviderContract
{
    public static function publish(ContainerContract $container): void
    {
    }

    #[Override]
    public function publishers(): array
    {
        return [];
    }
}
