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

namespace Valkyrja\Tests\Unit\Orm\Enum;

use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ComparisonTest extends TestCase
{
    public function testEqualsOperator(): void
    {
        self::assertSame('=', Comparison::EQUALS->value);
    }

    public function testNotEqualOperators(): void
    {
        self::assertSame('!=', Comparison::NOT_EQUAL->value);
        self::assertSame('<>', Comparison::NOT_EQUAL_ALT->value);
    }

    public function testComparisonOperators(): void
    {
        self::assertSame('>', Comparison::GREATER_THAN->value);
        self::assertSame('>=', Comparison::GREATER_THAN_EQUAL->value);
        self::assertSame('<', Comparison::LESS_THAN->value);
        self::assertSame('<=', Comparison::LESS_THAN_EQUAL->value);
    }

    public function testInOperators(): void
    {
        self::assertSame('IN', Comparison::IN->value);
        self::assertSame('NOT_IN', Comparison::NOT_IN->value);
    }

    public function testLikeOperators(): void
    {
        self::assertSame('LIKE', Comparison::LIKE->value);
        self::assertSame('NOT LIKE', Comparison::NOT_LIKE->value);
        self::assertSame('SOUNDS LIKE', Comparison::SOUNDS_LIKE->value);
        self::assertSame('RLIKE', Comparison::RLIKE->value);
    }

    public function testIsOperators(): void
    {
        self::assertSame('IS', Comparison::IS->value);
        self::assertSame('IS NOT', Comparison::IS_NOT->value);
    }

    public function testModOperators(): void
    {
        self::assertSame('%', Comparison::MOD->value);
        self::assertSame('MOD', Comparison::MOD_ALT->value);
    }

    public function testBitwiseOperators(): void
    {
        self::assertSame('>>', Comparison::RIGHT_SHIFT->value);
        self::assertSame('<<', Comparison::LEFT_SHIFT->value);
        self::assertSame('^', Comparison::BITWISE_XOR->value);
        self::assertSame('|', Comparison::BITWISE_OR->value);
        self::assertSame('~', Comparison::BITWISE_INVERSION->value);
    }

    public function testLogicalOperators(): void
    {
        self::assertSame('XOR', Comparison::LOGICAL_XOR->value);
    }

    public function testSpecialOperators(): void
    {
        self::assertSame('<=>', Comparison::NULL_SAFE_EQUALS->value);
        self::assertSame('MEMBER_OF', Comparison::MEMBER_OF->value);
        self::assertSame('REGEXP', Comparison::REGEXP->value);
        self::assertSame('NOT REGEXP', Comparison::NOT_REGEXP->value);
    }

    public function testCasesReturnsAllComparisons(): void
    {
        $cases = Comparison::cases();

        self::assertCount(27, $cases);
        self::assertContains(Comparison::EQUALS, $cases);
        self::assertContains(Comparison::NOT_EQUAL, $cases);
        self::assertContains(Comparison::GREATER_THAN, $cases);
        self::assertContains(Comparison::LESS_THAN, $cases);
        self::assertContains(Comparison::LIKE, $cases);
        self::assertContains(Comparison::IN, $cases);
    }
}
