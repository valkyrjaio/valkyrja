<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Dispatch\Data;

use JsonException;
use Valkyrja\Dispatch\Data\GlobalVariableDispatch;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the GlobalVariableDispatch.
 */
final class GlobalVariableDispatchTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testVariable(): void
    {
        $variable  = '_GET';
        $variable2 = '_POST';

        $dispatch = new GlobalVariableDispatch(variable: $variable);

        self::assertSame($variable, $dispatch->getVariable());

        $newDispatch = $dispatch->withVariable($variable2);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertSame($variable, $dispatch->getVariable());
        self::assertSame($variable2, $newDispatch->getVariable());
        self::assertSame($variable, $dispatch->__toString());
        self::assertSame($variable2, $newDispatch->__toString());
    }
}
