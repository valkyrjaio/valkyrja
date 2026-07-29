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
