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

namespace Valkyrja\Tests\Unit\Orm\Data\Where;

use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Data\Where\AndNotWhere;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\WhereType;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class AndNotWhereTest extends TestCase
{
    public function testExtendsWhere(): void
    {
        $value = new Value('column', 'test');
        $where = new AndNotWhere($value);

        self::assertInstanceOf(Where::class, $where);
    }

    public function testHasAndNotType(): void
    {
        $value = new Value('column', 'test');
        $where = new AndNotWhere($value);

        self::assertSame(WhereType::AND_NOT, $where->type);
    }

    public function testDefaultComparison(): void
    {
        $value = new Value('column', 'test');
        $where = new AndNotWhere($value);

        self::assertSame(Comparison::EQUALS, $where->comparison);
    }

    public function testCustomComparison(): void
    {
        $value = new Value('category', ['a', 'b', 'c']);
        $where = new AndNotWhere($value, Comparison::IN);

        self::assertSame(Comparison::IN, $where->comparison);
    }

    public function testToString(): void
    {
        $value = new Value('status', 'active');
        $where = new AndNotWhere($value);

        self::assertSame('AND NOT = :status', (string) $where);
    }

    public function testPreservesValue(): void
    {
        $value = new Value('column', 'test');
        $where = new AndNotWhere($value);

        self::assertSame($value, $where->value);
    }

    public function testToStringWithDifferentComparison(): void
    {
        $value = new Value('category', ['a', 'b']);
        $where = new AndNotWhere($value, Comparison::IN);

        self::assertSame('AND NOT IN (:category0, :category1)', (string) $where);
    }
}
