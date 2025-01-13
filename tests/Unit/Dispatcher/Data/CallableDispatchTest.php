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
use Valkyrja\Dispatcher\Data\CallableDispatch as Dispatch;
use Valkyrja\Tests\Classes\Dispatcher\InvalidDispatcherClass;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\Support\Arr;

use function json_encode;

use const JSON_THROW_ON_ERROR;

/**
 * Test the CallableDispatch.
 *
 * @author Melech Mizrachi
 */
class CallableDispatchTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testFromArray(): void
    {
        $callable = 'str_replace';
        $array    = [
            'callable'     => $callable,
            'arguments'    => null,
            'dependencies' => null,
        ];

        $dispatch = Dispatch::fromArray($array);

        self::assertSame($callable, $dispatch->getCallable());
        self::assertSame(Arr::toString($array), (string) $dispatch);
        self::assertSame($array, $dispatch->jsonSerialize());
        self::assertSame(Arr::toString($array), json_encode($dispatch, JSON_THROW_ON_ERROR));
    }

    public function testClass(): void
    {
        $callable  = 'str_replace';
        $callable2 = [InvalidDispatcherClass::class, 'staticMethod'];

        $dispatch = new Dispatch(callable: $callable);

        self::assertSame($callable, $dispatch->getCallable());

        $newDispatch = $dispatch->withCallable($callable2);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertSame($callable, $dispatch->getCallable());
        self::assertSame($callable2, $newDispatch->getCallable());
    }

    public function testArguments(): void
    {
        $callable  = 'str_replace';
        $arguments = ['test'];

        $dispatch = new Dispatch(callable: $callable);

        self::assertNull($dispatch->getArguments());

        $newDispatch = $dispatch->withArguments($arguments);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertNull($dispatch->getArguments());
        self::assertSame($arguments, $newDispatch->getArguments());
    }

    public function testDependencies(): void
    {
        $callable     = 'str_replace';
        $dependencies = ['test'];

        $dispatch = new Dispatch(callable: $callable);

        self::assertNull($dispatch->getDependencies());

        $newDispatch = $dispatch->withDependencies($dependencies);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertNull($dispatch->getDependencies());
        self::assertSame($dependencies, $newDispatch->getDependencies());
    }
}
