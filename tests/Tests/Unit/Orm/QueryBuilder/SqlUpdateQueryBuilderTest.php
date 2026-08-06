<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\UpdateQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\SqlUpdateQueryBuilder;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SqlUpdateQueryBuilderTest extends TestCase
{
    protected SqlUpdateQueryBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new SqlUpdateQueryBuilder('users');
    }

    public function testInstanceOfContracts(): void
    {
        self::assertInstanceOf(UpdateQueryBuilderContract::class, $this->builder);
        self::assertInstanceOf(QueryBuilderContract::class, $this->builder);
        self::assertInstanceOf(Stringable::class, $this->builder);
    }

    public function testDefaultQueryStructure(): void
    {
        $query = (string) $this->builder;

        self::assertSame('UPDATE users SET ', $query);
    }

    public function testWithSetReturnsNewInstance(): void
    {
        $value      = new Value('name', 'John');
        $newBuilder = $this->builder->withSet($value);

        self::assertNotSame($this->builder, $newBuilder);
    }

    public function testWithSetAddsSetClause(): void
    {
        $name  = new Value('name', 'John');
        $email = new Value('email', 'john@example.com');

        $newBuilder = $this->builder->withSet($name, $email);

        $query = (string) $newBuilder;

        self::assertSame('UPDATE users SET name = :name, email = :email', $query);
    }

    public function testSetClauseIsSeparatedFromTheTable(): void
    {
        $newBuilder = $this->builder->withSet(new Value('name', 'John'));

        self::assertSame('UPDATE users SET name = :name', (string) $newBuilder);
    }

    public function testSetClauseIsSeparatedFromTheAlias(): void
    {
        $newBuilder = $this->builder
            ->withAlias('u')
            ->withSet(new Value('name', 'John'));

        self::assertSame('UPDATE users u SET name = :name', (string) $newBuilder);
    }

    public function testWithAddedSetAppendsValues(): void
    {
        $name  = new Value('name', 'John');
        $email = new Value('email', 'john@example.com');
        $age   = new Value('age', 25);

        $newBuilder = $this->builder
            ->withSet($name, $email)
            ->withAddedSet($age);

        $query = (string) $newBuilder;

        self::assertSame('UPDATE users SET name = :name, email = :email, age = :age', $query);
    }

    public function testWithWhereAddsWhereClause(): void
    {
        $value = new Value('name', 'John');
        $where = new Where(new Value('id', 1));

        $newBuilder = $this->builder
            ->withSet($value)
            ->withWhere($where);

        $query = (string) $newBuilder;

        // The Where renders its own text, so the expectation pins the composition only.
        self::assertSame("UPDATE users SET name = :name WHERE $where", $query);
    }

    public function testWithFromReturnsNewInstance(): void
    {
        $newBuilder = $this->builder->withFrom('posts');

        self::assertNotSame($this->builder, $newBuilder);
        self::assertSame('UPDATE posts SET ', (string) $newBuilder);
    }

    public function testWithJoinAddsJoinClause(): void
    {
        $value = new Value('name', 'John');
        $join  = new Join(
            'profiles',
            'users.id',
            'profiles.user_id',
            Comparison::EQUALS,
            JoinOperator::ON,
            JoinType::INNER
        );

        $newBuilder = $this->builder
            ->withSet($value)
            ->withJoin($join);

        $query = (string) $newBuilder;

        self::assertSame("UPDATE users SET name = :name $join", $query);
    }

    public function testWithAddedJoinAppendsJoinClause(): void
    {
        $value = new Value('name', 'John');
        $join1 = new Join(
            'profiles',
            'users.id',
            'profiles.user_id',
            Comparison::EQUALS,
            JoinOperator::ON,
            JoinType::INNER
        );
        $join2 = new Join(
            'addresses',
            'users.id',
            'addresses.user_id',
            Comparison::EQUALS,
            JoinOperator::ON,
            JoinType::LEFT
        );

        $newBuilder = $this->builder
            ->withSet($value)
            ->withJoin($join1)
            ->withAddedJoin($join2);

        $query = (string) $newBuilder;

        self::assertSame("UPDATE users SET name = :name $join1 $join2", $query);
    }

    public function testImmutability(): void
    {
        $originalQuery = (string) $this->builder;

        $this->builder->withSet(new Value('name', 'John'));

        self::assertSame($originalQuery, (string) $this->builder);
    }

    public function testComplexUpdateQuery(): void
    {
        $name   = new Value('name', 'John');
        $email  = new Value('email', 'john@example.com');
        $where1 = new Where(new Value('id', 1));
        $where2 = new Where(new Value('active', true), Comparison::EQUALS, WhereType::AND);

        $newBuilder = $this->builder
            ->withSet($name, $email)
            ->withWhere($where1)
            ->withAddedWhere($where2);

        $query = (string) $newBuilder;

        self::assertSame(
            "UPDATE users SET name = :name, email = :email WHERE $where1 $where2",
            $query
        );
    }
}
