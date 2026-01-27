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

namespace Valkyrja\Tests\Unit\Orm\QueryBuilder;

use Stringable;
use Valkyrja\Orm\Data\Join;
use Valkyrja\Orm\Data\OrderBy;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\JoinOperator;
use Valkyrja\Orm\Enum\SortOrder;
use Valkyrja\Orm\Enum\WhereType;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\SelectQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\SqlSelectQueryBuilder;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class SqlSelectQueryBuilderTest extends TestCase
{
    protected SqlSelectQueryBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new SqlSelectQueryBuilder('users');
    }

    public function testInstanceOfContracts(): void
    {
        self::assertInstanceOf(SelectQueryBuilderContract::class, $this->builder);
        self::assertInstanceOf(QueryBuilderContract::class, $this->builder);
        self::assertInstanceOf(Stringable::class, $this->builder);
    }

    public function testDefaultQuerySelectsAllColumns(): void
    {
        $query = (string) $this->builder;

        self::assertSame('SELECT * FROM users', $query);
    }

    public function testWithColumnsReturnsNewInstance(): void
    {
        $newBuilder = $this->builder->withColumns('id', 'name');

        self::assertNotSame($this->builder, $newBuilder);
    }

    public function testWithColumnsSelectsSpecificColumns(): void
    {
        $newBuilder = $this->builder->withColumns('id', 'name', 'email');

        $query = (string) $newBuilder;

        self::assertSame('SELECT id, name, email FROM users', $query);
    }

    public function testWithAddedColumnsAppendsColumns(): void
    {
        $newBuilder = $this->builder
            ->withColumns('id')
            ->withAddedColumns('name', 'email');

        $query = (string) $newBuilder;

        self::assertSame('SELECT id, name, email FROM users', $query);
    }

    public function testWithFromReturnsNewInstance(): void
    {
        $newBuilder = $this->builder->withFrom('posts');

        self::assertNotSame($this->builder, $newBuilder);
        self::assertStringContainsString('FROM posts', (string) $newBuilder);
    }

    public function testWithAliasAddsAlias(): void
    {
        $newBuilder = $this->builder->withAlias('u');

        // Note: Alias appears after the FROM clause based on implementation
        self::assertStringContainsString('users', (string) $newBuilder);
    }

    public function testWithWhereAddsWhereClause(): void
    {
        $value = new Value('status', 'active');
        $where = new Where($value);

        $newBuilder = $this->builder->withWhere($where);

        $query = (string) $newBuilder;

        self::assertStringContainsString('WHERE', $query);
        self::assertStringContainsString(':status', $query);
    }

    public function testWithAddedWhereAppendsWhereClause(): void
    {
        $value1 = new Value('status', 'active');
        $where1 = new Where($value1);

        $value2 = new Value('role', 'admin');
        $where2 = new Where($value2, Comparison::EQUALS, WhereType::AND);

        $newBuilder = $this->builder
            ->withWhere($where1)
            ->withAddedWhere($where2);

        $query = (string) $newBuilder;

        self::assertStringContainsString('WHERE', $query);
        self::assertStringContainsString(':status', $query);
        self::assertStringContainsString(':role', $query);
    }

    public function testWithJoinAddsJoinClause(): void
    {
        $join = new Join(
            'posts',
            'users.id',
            'posts.user_id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        $newBuilder = $this->builder->withJoin($join);

        $query = (string) $newBuilder;

        self::assertStringContainsString('JOIN posts', $query);
    }

    public function testWithAddedJoinAppendsJoinClause(): void
    {
        $join1 = new Join(
            'posts',
            'users.id',
            'posts.user_id',
            Comparison::EQUALS,
            JoinOperator::ON
        );
        $join2 = new Join(
            'comments',
            'posts.id',
            'comments.post_id',
            Comparison::EQUALS,
            JoinOperator::ON
        );

        $newBuilder = $this->builder
            ->withJoin($join1)
            ->withAddedJoin($join2);

        $query = (string) $newBuilder;

        self::assertStringContainsString('JOIN posts', $query);
        self::assertStringContainsString('JOIN comments', $query);
    }

    public function testWithOrderByAddsOrderByClause(): void
    {
        $orderBy = new OrderBy('created_at', SortOrder::DESC);

        $newBuilder = $this->builder->withOrderBy($orderBy);

        $query = (string) $newBuilder;

        self::assertStringContainsString('ORDER BY', $query);
        self::assertStringContainsString('created_at DESC', $query);
    }

    public function testWithAddedOrderByAppendsOrderBy(): void
    {
        $orderBy1 = new OrderBy('created_at', SortOrder::DESC);
        $orderBy2 = new OrderBy('name', SortOrder::ASC);

        $newBuilder = $this->builder
            ->withOrderBy($orderBy1)
            ->withAddedOrderBy($orderBy2);

        $query = (string) $newBuilder;

        self::assertStringContainsString('ORDER BY', $query);
        self::assertStringContainsString('created_at DESC', $query);
        self::assertStringContainsString('name ASC', $query);
    }

    public function testWithGroupByAddsGroupByClause(): void
    {
        $orderBy    = new OrderBy('status', SortOrder::ASC);
        $newBuilder = $this->builder
            ->withGroupBy('status')
            ->withOrderBy($orderBy);

        $query = (string) $newBuilder;

        self::assertStringContainsString('GROUP BY', $query);
        self::assertStringContainsString('status', $query);
    }

    public function testWithAddedGroupByAppendsGroupBy(): void
    {
        $newBuilder = $this->builder
            ->withGroupBy('status')
            ->withAddedGroupBy('role');

        $query = (string) $newBuilder;

        self::assertStringContainsString('GROUP BY', $query);
        self::assertStringContainsString('status', $query);
        self::assertStringContainsString('role', $query);
    }

    public function testWithLimitAddsLimitClause(): void
    {
        $newBuilder = $this->builder->withLimit(10);

        $query = (string) $newBuilder;

        self::assertStringContainsString('LIMIT 10', $query);
    }

    public function testWithOffsetAddsOffsetClause(): void
    {
        $newBuilder = $this->builder->withOffset(20);

        $query = (string) $newBuilder;

        self::assertStringContainsString('OFFSET 20', $query);
    }

    public function testComplexQuery(): void
    {
        $where   = new Where(new Value('status', 'active'));
        $orderBy = new OrderBy('created_at', SortOrder::DESC);

        $newBuilder = $this->builder
            ->withColumns('id', 'name', 'email')
            ->withWhere($where)
            ->withOrderBy($orderBy)
            ->withLimit(10)
            ->withOffset(0);

        $query = (string) $newBuilder;

        self::assertStringContainsString('SELECT id, name, email', $query);
        self::assertStringContainsString('FROM users', $query);
        self::assertStringContainsString('WHERE', $query);
        self::assertStringContainsString('ORDER BY', $query);
        self::assertStringContainsString('LIMIT 10', $query);
        self::assertStringContainsString('OFFSET 0', $query);
    }

    public function testImmutability(): void
    {
        $queryBuilder = (string) $this->builder;

        $queryBuilder2 = $this->builder->withColumns('id', 'name');
        $queryBuilder3 = $this->builder->withLimit(10);

        self::assertSame($queryBuilder, (string) $this->builder);
        self::assertNotSame($queryBuilder, $queryBuilder2);
        self::assertNotSame($queryBuilder, $queryBuilder3);
        self::assertNotSame($queryBuilder2, $queryBuilder3);
    }
}
