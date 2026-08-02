<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Orm\Data\Join;

use Valkyrja\Orm\Data\Join;
use Valkyrja\Orm\Data\Join\LeftJoin;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\JoinOperator;
use Valkyrja\Orm\Enum\JoinType;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class LeftJoinTest extends TestCase
{
    public function testExtendsJoin(): void
    {
        $join = new LeftJoin(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        self::assertInstanceOf(Join::class, $join);
    }

    public function testHasLeftJoinType(): void
    {
        $join = new LeftJoin(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        self::assertSame(JoinType::LEFT, $join->type);
    }

    public function testToString(): void
    {
        $join = new LeftJoin(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        self::assertSame('LEFT JOIN users ON posts.user_id = users.id', (string) $join);
    }

    public function testPreservesTableProperty(): void
    {
        $join = new LeftJoin(
            'custom_table',
            'a.id',
            'b.id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        self::assertSame('custom_table', $join->table);
    }

    public function testPreservesColumnProperty(): void
    {
        $join = new LeftJoin(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        self::assertSame('posts.user_id', $join->column);
    }

    public function testPreservesJoinColumnProperty(): void
    {
        $join = new LeftJoin(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        self::assertSame('users.id', $join->joinColumn);
    }

    public function testPreservesComparisonProperty(): void
    {
        $join = new LeftJoin(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::NOT_EQUAL,
            JoinOperator::ON
        );

        self::assertSame(Comparison::NOT_EQUAL, $join->comparison);
    }

    public function testPreservesOperatorProperty(): void
    {
        $join = new LeftJoin(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::WHERE
        );

        self::assertSame(JoinOperator::WHERE, $join->operator);
    }

    public function testToStringWithWhereOperator(): void
    {
        $join = new LeftJoin(
            'users',
            'posts.user_id',
            'users.id',
            Comparison::EQUALS,
            JoinOperator::WHERE
        );

        self::assertSame('LEFT JOIN users WHERE posts.user_id = users.id', (string) $join);
    }
}
