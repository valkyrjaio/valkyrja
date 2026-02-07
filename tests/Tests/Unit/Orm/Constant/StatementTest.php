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

namespace Valkyrja\Tests\Unit\Orm\Constant;

use Valkyrja\Orm\Constant\Statement;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class StatementTest extends TestCase
{
    public function testSelectConstant(): void
    {
        self::assertSame('SELECT', Statement::SELECT);
    }

    public function testInsertConstant(): void
    {
        self::assertSame('INSERT', Statement::INSERT);
    }

    public function testIntoConstant(): void
    {
        self::assertSame('INTO', Statement::INTO);
    }

    public function testUpdateConstant(): void
    {
        self::assertSame('UPDATE', Statement::UPDATE);
    }

    public function testDeleteConstant(): void
    {
        self::assertSame('DELETE', Statement::DELETE);
    }

    public function testJoinConstant(): void
    {
        self::assertSame('JOIN', Statement::JOIN);
    }

    public function testInnerConstant(): void
    {
        self::assertSame('INNER', Statement::INNER);
    }

    public function testOuterConstant(): void
    {
        self::assertSame('OUTER', Statement::OUTER);
    }

    public function testLeftConstant(): void
    {
        self::assertSame('LEFT', Statement::LEFT);
    }

    public function testRightConstant(): void
    {
        self::assertSame('RIGHT', Statement::RIGHT);
    }

    public function testCountConstant(): void
    {
        self::assertSame('COUNT', Statement::COUNT);
    }

    public function testDistinctConstant(): void
    {
        self::assertSame('DISTINCT', Statement::DISTINCT);
    }

    public function testOnConstant(): void
    {
        self::assertSame('ON', Statement::ON);
    }

    public function testAsConstant(): void
    {
        self::assertSame('AS', Statement::AS);
    }

    public function testFromConstant(): void
    {
        self::assertSame('FROM', Statement::FROM);
    }

    public function testSetConstant(): void
    {
        self::assertSame('SET', Statement::SET);
    }

    public function testValuesConstant(): void
    {
        self::assertSame('VALUES', Statement::VALUES);
    }

    public function testWhereConstant(): void
    {
        self::assertSame('WHERE', Statement::WHERE);
    }

    public function testWhereAndConstant(): void
    {
        self::assertSame('AND', Statement::WHERE_AND);
    }

    public function testWhereOrConstant(): void
    {
        self::assertSame('OR', Statement::WHERE_OR);
    }

    public function testGroupByConstant(): void
    {
        self::assertSame('GROUP BY', Statement::GROUP_BY);
    }

    public function testOrderByConstant(): void
    {
        self::assertSame('ORDER BY', Statement::ORDER_BY);
    }

    public function testLimitConstant(): void
    {
        self::assertSame('LIMIT', Statement::LIMIT);
    }

    public function testOffsetConstant(): void
    {
        self::assertSame('OFFSET', Statement::OFFSET);
    }

    public function testCountAllConstant(): void
    {
        self::assertSame('COUNT(*)', Statement::COUNT_ALL);
    }
}
