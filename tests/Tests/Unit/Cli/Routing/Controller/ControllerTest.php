<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Routing\Controller;

use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Tests\Fixtures\Cli\Routing\Controller\ControllerFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ControllerTest extends TestCase
{
    public function testConstruct(): void
    {
        $input         = new Input();
        $outputFactory = new OutputFactory();
        $controller    = new ControllerFixture(
            input: $input,
            outputFactory: $outputFactory,
        );

        self::assertSame($input, $controller->getInput());
        self::assertSame($outputFactory, $controller->getOutputFactory());
    }
}
