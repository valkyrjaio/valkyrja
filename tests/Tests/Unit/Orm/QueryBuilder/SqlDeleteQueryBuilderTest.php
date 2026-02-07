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
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\JoinOperator;
use Valkyrja\Orm\Enum\JoinType;
use Valkyrja\Orm\Enum\WhereType;
use Valkyrja\Orm\QueryBuilder\Contract\DeleteQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\SqlDeleteQueryBuilder;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class SqlDeleteQueryBuilderTest extends TestCase
{
    protected SqlDeleteQueryBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new SqlDeleteQueryBuilder('users');
    }

    public function testInstanceOfContracts(): void
    {
        self::assertInstanceOf(DeleteQueryBuilderContract::class, $this->builder);
        self::assertInstanceOf(QueryBuilderContract::class, $this->builder);
        self::assertInstanceOf(Stringable::class, $this->builder);
    }

    public function testDefaultQueryStructure(): void
    {
        $query = (string) $this->builder;

        self::assertSame('DELETE FROM users', $query);
    }

    public function testWithFromReturnsNewInstance(): void
    {
        $newBuilder = $this->builder->withFrom('posts');

        self::assertNotSame($this->builder, $newBuilder);
        self::assertStringContainsString('FROM posts', (string) $newBuilder);
    }

    public function testWithWhereAddsWhereClause(): void
    {
        $where = new Where(new Value('id', 1));

        $newBuilder = $this->builder->withWhere($where);

        $query = (string) $newBuilder;

        self::assertStringContainsString('DELETE FROM users', $query);
        self::assertStringContainsString('WHERE', $query);
        self::assertStringContainsString(':id', $query);
    }

    public function testWithAddedWhereAppendsWhereClause(): void
    {
        $where1 = new Where(new Value('id', 1));
        $where2 = new Where(new Value('active', false), Comparison::EQUALS, WhereType::AND);

        $newBuilder = $this->builder
            ->withWhere($where1)
            ->withAddedWhere($where2);

        $query = (string) $newBuilder;

        self::assertStringContainsString('WHERE', $query);
        self::assertStringContainsString(':id', $query);
        self::assertStringContainsString(':active', $query);
        self::assertStringContainsString('AND', $query);
    }

    public function testWithAliasAddsAlias(): void
    {
        $newBuilder = $this->builder->withAlias('u');

        $query = (string) $newBuilder;

        self::assertStringContainsString('users', $query);
    }

    public function testWithJoinAddsJoinClause(): void
    {
        $join = new Join(
            'posts',
            'users.id',
            'posts.user_id',
            Comparison::EQUALS,
            JoinOperator::ON,
            JoinType::LEFT
        );

        $newBuilder = $this->builder->withJoin($join);

        $query = (string) $newBuilder;

        self::assertStringContainsString('LEFT JOIN posts', $query);
    }

    public function testWithAddedJoinAppendsJoinClause(): void
    {
        $join1 = new Join(
            'posts',
            'users.id',
            'posts.user_id',
            Comparison::EQUALS,
            JoinOperator::ON,
            JoinType::LEFT
        );
        $join2 = new Join(
            'comments',
            'posts.id',
            'comments.post_id',
            Comparison::EQUALS,
            JoinOperator::ON,
            JoinType::INNER
        );

        $newBuilder = $this->builder
            ->withJoin($join1)
            ->withAddedJoin($join2);

        $query = (string) $newBuilder;

        self::assertStringContainsString('LEFT JOIN posts', $query);
        self::assertStringContainsString('INNER JOIN comments', $query);
    }

    public function testImmutability(): void
    {
        $originalQuery = (string) $this->builder;

        $this->builder->withWhere(new Where(new Value('id', 1)));

        self::assertSame($originalQuery, (string) $this->builder);
    }

    public function testComplexDeleteQuery(): void
    {
        $where1 = new Where(new Value('status', 'inactive'));
        $where2 = new Where(new Value('created_at', '2020-01-01'), Comparison::LESS_THAN, WhereType::AND);

        $newBuilder = $this->builder
            ->withWhere($where1)
            ->withAddedWhere($where2);

        $query = (string) $newBuilder;

        self::assertStringContainsString('DELETE FROM users', $query);
        self::assertStringContainsString('WHERE', $query);
        self::assertStringContainsString(':status', $query);
        self::assertStringContainsString(':created_at', $query);
        self::assertStringContainsString('<', $query);
    }
}
