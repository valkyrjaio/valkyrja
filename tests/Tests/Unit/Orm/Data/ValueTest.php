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

namespace Valkyrja\Tests\Unit\Orm\Data;

use ReflectionClass;
use Stringable;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilderContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ValueTest extends TestCase
{
    public function testImplementsStringable(): void
    {
        $value = new Value('column', 'test');

        self::assertInstanceOf(Stringable::class, $value);
    }

    public function testNameProperty(): void
    {
        $value = new Value('column', 'test');

        self::assertSame('column', $value->name);
    }

    public function testValueProperty(): void
    {
        $value = new Value('column', 'test');

        self::assertSame('test', $value->value);
    }

    public function testValueCanBeNull(): void
    {
        $value = new Value('column', null);

        self::assertNull($value->value);
    }

    public function testValueCanBeInteger(): void
    {
        $value = new Value('id', 123);

        self::assertSame(123, $value->value);
    }

    public function testValueCanBeFloat(): void
    {
        $value = new Value('price', 19.99);

        self::assertSame(19.99, $value->value);
    }

    public function testValueCanBeBool(): void
    {
        $value = new Value('active', true);

        self::assertTrue($value->value);
    }

    public function testToStringReturnsBindParameter(): void
    {
        $value = new Value('column', 'test');

        self::assertSame(':column', (string) $value);
    }

    public function testToStringWithArrayValue(): void
    {
        $value = new Value('status', ['active', 'pending', 'completed']);

        $result = (string) $value;

        self::assertStringContainsString(':status', $result);
        self::assertStringStartsWith('(', $result);
        self::assertStringEndsWith(')', $result);
    }

    public function testToStringWithQueryBuilder(): void
    {
        $queryBuilder = $this->createMock(QueryBuilderContract::class);
        $queryBuilder
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('SELECT * FROM users');

        $value = new Value('subquery', $queryBuilder);

        self::assertSame('(SELECT * FROM users)', (string) $value);
    }

    public function testValueDefaultsToNull(): void
    {
        $value = new Value('column');

        self::assertNull($value->value);
    }

    public function testReadonlyClass(): void
    {
        $reflection = new ReflectionClass(Value::class);

        self::assertTrue($reflection->isReadOnly());
    }
}
