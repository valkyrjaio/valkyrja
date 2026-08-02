<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Routing\Handler;

use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;

/**
 * A route handler matching the signature a CLI route declares, for tests that
 * need a handler but never dispatch it.
 */
final class RouteHandlerFixture
{
    public static function handle(ContainerContract $container, RouteContract $route): OutputContract
    {
        return new Output();
    }
}
