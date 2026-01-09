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

namespace Valkyrja\Tests\Unit\Http\Message\Header\Value;

use JsonException;
use Valkyrja\Http\Message\Header\Throwable\Exception\UnsupportedOffsetSetException;
use Valkyrja\Http\Message\Header\Throwable\Exception\UnsupportedOffsetUnsetException;
use Valkyrja\Http\Message\Header\Value\Value;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function array_merge;
use function implode;
use function json_encode;

use const JSON_THROW_ON_ERROR;

class ValueTest extends TestCase
{
    public function testFromValue(): void
    {
        $value  = Value::fromValue('');
        $value2 = Value::fromValue('test');
        $value3 = Value::fromValue('test2;foo;bar');

        self::assertCount(1, $value->getComponents());
        self::assertSame('', $value->__toString());

        self::assertCount(1, $value2->getComponents());
        self::assertSame('test', $value2->__toString());

        self::assertCount(3, $value3->getComponents());
        self::assertSame('test2;foo;bar', $value3->__toString());
    }

    /**
     * @throws JsonException
     */
    public function testValue(): void
    {
        $components       = ['test'];
        $components2      = ['test2', 'test3'];
        $components3      = ['foo'];
        $components4      = ['bar', 'bar2'];
        $addedComponents  = ['bar3'];
        $addedComponents2 = ['foo2', 'foo3'];

        $componentsString       = implode(';', $components);
        $components2String      = implode(';', $components2);
        $components3String      = implode(';', $components3);
        $components4String      = implode(';', $components4);
        $addedComponentsString  = implode(';', array_merge($components, $addedComponents));
        $addedComponents2String = implode(';', array_merge($components, $addedComponents2));

        $single = new Value(...$components);
        $multi  = new Value(...$components2);

        $withSingle = $single->withComponents(...$components3);
        $withMulti  = $single->withComponents(...$components4);

        $addedSingle = $single->withAddedComponents(...$addedComponents);
        $addedMulti  = $single->withAddedComponents(...$addedComponents2);

        self::assertNotSame($single, $withSingle);
        self::assertNotSame($single, $withMulti);
        self::assertNotSame($single, $addedSingle);
        self::assertNotSame($single, $addedMulti);

        self::assertCount(1, $single->getComponents());
        self::assertCount(1, $single);
        self::assertSame(1, $single->count());
        self::assertSame($componentsString, $single->__toString());
        self::assertSame($componentsString, $single->jsonSerialize());
        self::assertSame("\"$componentsString\"", json_encode($single, JSON_THROW_ON_ERROR));

        self::assertCount(2, $multi->getComponents());
        self::assertCount(2, $multi);
        self::assertSame(2, $multi->count());
        self::assertSame($components2String, $multi->__toString());
        self::assertSame($components2String, $multi->jsonSerialize());
        self::assertSame("\"$components2String\"", json_encode($multi, JSON_THROW_ON_ERROR));

        self::assertCount(1, $withSingle->getComponents());
        self::assertCount(1, $withSingle);
        self::assertSame(1, $withSingle->count());
        self::assertSame($components3String, $withSingle->__toString());
        self::assertSame($components3String, $withSingle->jsonSerialize());
        self::assertSame("\"$components3String\"", json_encode($withSingle, JSON_THROW_ON_ERROR));

        self::assertCount(2, $withMulti->getComponents());
        self::assertCount(2, $withMulti);
        self::assertSame(2, $withMulti->count());
        self::assertSame($components4String, $withMulti->__toString());
        self::assertSame($components4String, $withMulti->jsonSerialize());
        self::assertSame("\"$components4String\"", json_encode($withMulti, JSON_THROW_ON_ERROR));

        self::assertCount(2, $addedSingle->getComponents());
        self::assertCount(2, $addedSingle);
        self::assertSame(2, $addedSingle->count());
        self::assertSame($addedComponentsString, $addedSingle->__toString());
        self::assertSame($addedComponentsString, $addedSingle->jsonSerialize());
        self::assertSame("\"$addedComponentsString\"", json_encode($addedSingle, JSON_THROW_ON_ERROR));

        self::assertCount(3, $addedMulti->getComponents());
        self::assertCount(3, $addedMulti);
        self::assertSame(3, $addedMulti->count());
        self::assertSame($addedComponents2String, $addedMulti->__toString());
        self::assertSame($addedComponents2String, $addedMulti->jsonSerialize());
        self::assertSame("\"$addedComponents2String\"", json_encode($addedMulti, JSON_THROW_ON_ERROR));
    }

    public function testIteration(): void
    {
        $components = ['test', 'foo', 'bar'];

        $value = new Value(...$components);

        self::assertCount(3, $value);
        self::assertSame(3, $value->count());
        self::assertSame(0, $value->key());

        foreach ($value as $key => $val) {
            self::assertSame($key, $value->key());
            self::assertTrue($value->valid());
            self::assertTrue(isset($value[$key]));
            self::assertSame($val, $value->current());
            self::assertSame($val, $value[$key]);
        }
    }

    public function testUnsupportedOffsetSetException(): void
    {
        $this->expectException(UnsupportedOffsetSetException::class);

        $value    = new Value('test');
        $value[1] = 'fire';
    }

    public function testUnsupportedOffsetUnsetException(): void
    {
        $this->expectException(UnsupportedOffsetUnsetException::class);

        $value = new Value('test');

        unset($value[0]);
    }
}
