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
use Valkyrja\Dispatcher\Data\PropertyDispatch as Dispatch;
use Valkyrja\Tests\Classes\Dispatcher\InvalidDispatcherClass;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\Support\Arr;

use function json_encode;

use const JSON_THROW_ON_ERROR;

/**
 * Test the PropertyDispatch.
 *
 * @author Melech Mizrachi
 */
class PropertyDispatchTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testFromArray(): void
    {
        $class    = InvalidDispatcherClass::class;
        $property = 'TEST';
        $array    = [
            'class'        => $class,
            'arguments'    => null,
            'dependencies' => null,
            'property'     => $property,
            'isStatic'     => false,
        ];

        $dispatch = Dispatch::fromArray($array);

        self::assertSame($property, $dispatch->getProperty());
        self::assertSame(Arr::toString($array), (string) $dispatch);
        self::assertSame($array, $dispatch->jsonSerialize());
        self::assertSame(Arr::toString($array), json_encode($dispatch, JSON_THROW_ON_ERROR));
    }

    public function testMethod(): void
    {
        $class     = InvalidDispatcherClass::class;
        $property  = 'TEST';
        $property2 = 'TEST2';

        $dispatch = new Dispatch(class: $class, property: $property);

        self::assertSame($property, $dispatch->getProperty());

        $newDispatch = $dispatch->withProperty($property2);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertSame($property, $dispatch->getProperty());
        self::assertSame($property2, $newDispatch->getProperty());
    }

    public function testIsStatic(): void
    {
        $class    = InvalidDispatcherClass::class;
        $property = 'TEST';
        $dispatch = new Dispatch(class: $class, property: $property);

        self::assertFalse($dispatch->isStatic());

        $newDispatch = $dispatch->withIsStatic(true);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertFalse($dispatch->isStatic());
        self::assertTrue($newDispatch->isStatic());
    }
}
