<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Routing\Controller;

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;

abstract class Controller
{
    public function __construct(
        protected InputContract $input,
        protected OutputFactoryContract $outputFactory,
    ) {
    }
}
