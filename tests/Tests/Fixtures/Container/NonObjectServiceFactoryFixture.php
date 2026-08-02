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
 * A service factory shaped the way the container declares one, whose body hands back
 * nothing.
 *
 * The container guards against a binding that fails to produce an object, and a
 * well-behaved factory can never reach that guard. Building the misbehaving one is
 * the only thing this fixture does.
 */
final class NonObjectServiceFactoryFixture
{
    /**
     * @return Closure(ContainerContract, array<array-key, mixed>): object
     */
    public static function create(): Closure
    {
        /** @var Closure(ContainerContract, array<array-key, mixed>): object $factory */
        $factory = Closure::fromCallable(
            static fn (ContainerContract $container, array $arguments = []): object|null => null
        );

        return $factory;
    }
}
