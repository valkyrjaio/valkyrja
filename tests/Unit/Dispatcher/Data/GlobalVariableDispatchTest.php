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

namespace Valkyrja\Tests\Unit\Dispatcher\Data;

use JsonException;
use Valkyrja\Dispatcher\Data\GlobalVariableDispatch as Dispatch;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the GlobalVariableDispatch.
 *
 * @author Melech Mizrachi
 */
class GlobalVariableDispatchTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testVariable(): void
    {
        $variable  = '_GET';
        $variable2 = '_POST';

        $dispatch = new Dispatch(variable: $variable);

        self::assertSame($variable, $dispatch->getVariable());

        $newDispatch = $dispatch->withVariable($variable2);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertSame($variable, $dispatch->getVariable());
        self::assertSame($variable2, $newDispatch->getVariable());
        self::assertSame($variable, $dispatch->__toString());
        self::assertSame($variable2, $newDispatch->__toString());
    }
}
