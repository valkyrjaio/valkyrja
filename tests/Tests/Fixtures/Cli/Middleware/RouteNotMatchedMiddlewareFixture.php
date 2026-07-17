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

namespace Valkyrja\Tests\Fixtures\Cli\Middleware;

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Tests\Fixtures\Cli\Middleware\Trait\MiddlewareCounterTrait;

final class RouteNotMatchedMiddlewareFixture implements RouteNotMatchedMiddlewareContract
{
    use MiddlewareCounterTrait;

    public function routeNotMatched(InputContract $input, OutputContract $output, RouteNotMatchedHandlerContract $handler): OutputContract
    {
        $this->updateCounter();

        return $handler->routeNotMatched($input, $output);
    }
}
