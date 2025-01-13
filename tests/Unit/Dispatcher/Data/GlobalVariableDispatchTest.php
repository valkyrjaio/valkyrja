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

namespace Unit\Dispatcher\Data;

use JsonException;
use Valkyrja\Dispatcher\Data\GlobalVariableDispatch as Dispatch;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\Support\Arr;

use function json_encode;

use const JSON_THROW_ON_ERROR;

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
    public function testFromArray(): void
    {
        $variable = '_GET';
        $array    = [
            'variable' => $variable,
        ];

        $dispatch = Dispatch::fromArray($array);

        self::assertSame($variable, $dispatch->getVariable());
        self::assertSame(Arr::toString($array), (string) $dispatch);
        self::assertSame($array, $dispatch->jsonSerialize());
        self::assertSame(Arr::toString($array), json_encode($dispatch, JSON_THROW_ON_ERROR));
    }

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
    }
}
