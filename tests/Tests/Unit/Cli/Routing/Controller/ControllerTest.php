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

namespace Valkyrja\Tests\Unit\Cli\Routing\Controller;

use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Tests\Classes\Cli\Routing\Controller\ControllerClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ControllerTest extends TestCase
{
    public function testConstruct(): void
    {
        $input         = new Input();
        $outputFactory = new OutputFactory();
        $controller    = new ControllerClass(
            input: $input,
            outputFactory: $outputFactory,
        );

        self::assertSame($input, $controller->getInput());
        self::assertSame($outputFactory, $controller->getOutputFactory());
    }
}
