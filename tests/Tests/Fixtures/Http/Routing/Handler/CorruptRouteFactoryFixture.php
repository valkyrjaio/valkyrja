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

namespace Valkyrja\Tests\Fixtures\Http\Routing\Handler;

use Closure;
use stdClass;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

/**
 * A route factory shaped the way generated routing data declares one, whose body
 * hands back the wrong type.
 *
 * Routing data is generated, so the framework trusts its declared shape and lets the
 * return boundary raise a TypeError when the generated cache does not match. Reaching
 * that boundary needs a factory the type system would otherwise reject, which is the
 * one thing this fixture exists to build.
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
