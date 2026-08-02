<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Routing\Controller;

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Controller\Controller;

final class ControllerFixture extends Controller
{
    public function getInput(): InputContract
    {
        return $this->input;
    }

    public function getOutputFactory(): OutputFactoryContract
    {
        return $this->outputFactory;
    }
}
