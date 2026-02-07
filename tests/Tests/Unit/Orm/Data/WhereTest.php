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
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\WhereType;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class WhereTest extends TestCase
{
    public function testImplementsStringable(): void
    {
        $value = new Value('column', 'test');
        $where = new Where($value);

        self::assertInstanceOf(Stringable::class, $where);
    }

    public function testValueProperty(): void
    {
        $value = new Value('column', 'test');
        $where = new Where($value);

        self::assertSame($value, $where->value);
    }

    public function testDefaultComparison(): void
    {
        $value = new Value('column', 'test');
        $where = new Where($value);

        self::assertSame(Comparison::EQUALS, $where->comparison);
    }

    public function testCustomComparison(): void
    {
        $value = new Value('age', 18);
        $where = new Where($value, Comparison::GREATER_THAN);

        self::assertSame(Comparison::GREATER_THAN, $where->comparison);
    }

    public function testDefaultWhereType(): void
    {
        $value = new Value('column', 'test');
        $where = new Where($value);

        self::assertSame(WhereType::DEFAULT, $where->type);
    }

    public function testCustomWhereType(): void
    {
        $value = new Value('column', 'test');
        $where = new Where($value, Comparison::EQUALS, WhereType::AND);

        self::assertSame(WhereType::AND, $where->type);
    }

    public function testToStringWithDefaultType(): void
    {
        $value = new Value('column', 'test');
        $where = new Where($value);

        self::assertSame(' = :column', (string) $where);
    }

    public function testToStringWithAndType(): void
    {
        $value = new Value('column', 'test');
        $where = new Where($value, Comparison::EQUALS, WhereType::AND);

        self::assertSame('AND = :column', (string) $where);
    }

    public function testToStringWithOrType(): void
    {
        $value = new Value('status', 'active');
        $where = new Where($value, Comparison::EQUALS, WhereType::OR);

        self::assertSame('OR = :status', (string) $where);
    }

    public function testToStringWithGreaterThan(): void
    {
        $value = new Value('age', 18);
        $where = new Where($value, Comparison::GREATER_THAN, WhereType::AND);

        self::assertSame('AND > :age', (string) $where);
    }

    public function testToStringWithLike(): void
    {
        $value = new Value('name', '%john%');
        $where = new Where($value, Comparison::LIKE);

        self::assertSame(' LIKE :name', (string) $where);
    }

    public function testToStringWithIn(): void
    {
        $value = new Value('status', ['active', 'pending']);
        $where = new Where($value, Comparison::IN);

        $result = (string) $where;

        self::assertStringContainsString('IN', $result);
        self::assertStringContainsString(':status', $result);
    }

    public function testReadonlyClass(): void
    {
        $reflection = new ReflectionClass(Where::class);

        self::assertTrue($reflection->isReadOnly());
    }
}
