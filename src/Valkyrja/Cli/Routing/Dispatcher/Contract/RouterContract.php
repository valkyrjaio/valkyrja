<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Routing\Dispatcher\Contract;

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

interface RouterContract
{
    /**
     * Dispatch an input and return an output.
     */
    public function dispatch(InputContract $input): OutputContract;

    /**
     * Dispatch an input for a specific route.
     */
    public function dispatchRoute(InputContract $input, RouteContract $route): OutputContract;
}
