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
use Valkyrja\Orm\Data\Join;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\JoinOperator;
use Valkyrja\Orm\Enum\JoinType;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class JoinTest extends TestCase
{
    public function testImplementsStringable(): void
    {
        $join = new Join(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        self::assertInstanceOf(Stringable::class, $join);
    }

    public function testTableProperty(): void
    {
        $join = new Join(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        self::assertSame('users', $join->table);
    }

    public function testColumnProperty(): void
    {
        $join = new Join(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        self::assertSame('posts.user_id', $join->column);
    }

    public function testJoinColumnProperty(): void
    {
        $join = new Join(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        self::assertSame('users.id', $join->joinColumn);
    }

    public function testComparisonProperty(): void
    {
        $join = new Join(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        self::assertSame(Comparison::EQUALS, $join->comparison);
    }

    public function testOperatorProperty(): void
    {
        $join = new Join(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        self::assertSame(JoinOperator::ON, $join->operator);
    }

    public function testDefaultJoinType(): void
    {
        $join = new Join(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        self::assertSame(JoinType::DEFAULT, $join->type);
    }

    public function testCustomJoinType(): void
    {
        $join = new Join(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON,
            JoinType::LEFT
        );

        self::assertSame(JoinType::LEFT, $join->type);
    }

    public function testToStringWithDefaultJoinType(): void
    {
        $join = new Join(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        self::assertSame(' JOIN users ON posts.user_id = users.id', (string) $join);
    }

    public function testToStringWithLeftJoin(): void
    {
        $join = new Join(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON,
            JoinType::LEFT
        );

        self::assertSame('LEFT JOIN users ON posts.user_id = users.id', (string) $join);
    }

    public function testToStringWithInnerJoin(): void
    {
        $join = new Join(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON,
            JoinType::INNER
        );

        self::assertSame('INNER JOIN users ON posts.user_id = users.id', (string) $join);
    }

    public function testToStringWithRightJoin(): void
    {
        $join = new Join(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON,
            JoinType::RIGHT
        );

        self::assertSame('RIGHT JOIN users ON posts.user_id = users.id', (string) $join);
    }

    public function testToStringWithWhereOperator(): void
    {
        $join = new Join(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::WHERE,
            JoinType::LEFT
        );

        self::assertSame('LEFT JOIN users WHERE posts.user_id = users.id', (string) $join);
    }

    public function testReadonlyClass(): void
    {
        $reflection = new ReflectionClass(Join::class);

        self::assertTrue($reflection->isReadOnly());
    }
}
