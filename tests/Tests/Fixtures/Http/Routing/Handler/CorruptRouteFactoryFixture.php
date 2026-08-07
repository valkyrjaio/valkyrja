<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Routing\Handler;

use Closure;
use stdClass;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

/**
 * A route factory shaped the way generated routing data declares one, whose body hands back the wrong type.
 */
final class CorruptRouteFactoryFixture
{
    /**
     * @return Closure(): RouteContract
     */
    public static function create(): Closure
    {
        /** @var Closure(): RouteContract $factory */
        $factory = Closure::fromCallable(static fn (): object => new stdClass());

        return $factory;
    }
}
