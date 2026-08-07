<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Container;

use Closure;
use Valkyrja\Container\Manager\Contract\ContainerContract;

/**
 * A service factory shaped the way the container declares one, whose body hands back nothing.
 */
final class NonObjectServiceFactoryFixture
{
    /**
     * Get the factory, whose body returns null rather than an object.
     */
    public static function create(): Closure
    {
        return Closure::fromCallable(
            static fn (ContainerContract $container, array $arguments = []): object|null => null
        );
    }
}
