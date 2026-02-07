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
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\JoinOperator;
use Valkyrja\Orm\Enum\JoinType;
use Valkyrja\Orm\QueryBuilder\Contract\InsertQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\SqlInsertQueryBuilder;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SqlInsertQueryBuilderTest extends TestCase
{
    protected SqlInsertQueryBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new SqlInsertQueryBuilder('users');
    }

    public function testInstanceOfContracts(): void
    {
        self::assertInstanceOf(InsertQueryBuilderContract::class, $this->builder);
        self::assertInstanceOf(QueryBuilderContract::class, $this->builder);
        self::assertInstanceOf(Stringable::class, $this->builder);
    }

    public function testDefaultQueryStructure(): void
    {
        $query = (string) $this->builder;

        self::assertStringContainsString('INSERT INTO users', $query);
    }

    public function testWithSetReturnsNewInstance(): void
    {
        $value      = new Value('name', 'John');
        $newBuilder = $this->builder->withSet($value);

        self::assertNotSame($this->builder, $newBuilder);
    }

    public function testWithSetAddsValues(): void
    {
        $name  = new Value('name', 'John');
        $email = new Value('email', 'john@example.com');

        $newBuilder = $this->builder->withSet($name, $email);

        $query = (string) $newBuilder;

        self::assertStringContainsString('INSERT INTO users', $query);
        self::assertStringContainsString('(name, email)', $query);
        self::assertStringContainsString('VALUES', $query);
        self::assertStringContainsString(':name', $query);
        self::assertStringContainsString(':email', $query);
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

        self::assertStringContainsString('name', $query);
        self::assertStringContainsString('email', $query);
        self::assertStringContainsString('age', $query);
    }

    public function testWithFromReturnsNewInstance(): void
    {
        $newBuilder = $this->builder->withFrom('posts');

        self::assertNotSame($this->builder, $newBuilder);
        self::assertStringContainsString('INTO posts', (string) $newBuilder);
    }

    public function testWithAliasAddsAlias(): void
    {
        $name       = new Value('name', 'John');
        $newBuilder = $this->builder
            ->withAlias('u')
            ->withSet($name);

        $query = (string) $newBuilder;

        self::assertStringContainsString('users', $query);
    }

    public function testWithJoinAddsJoinClause(): void
    {
        $value = new Value('name', 'John');
        $join  = new Join(
            'other_table',
            'users.id',
            'other_table.user_id',
            Comparison::EQUALS,
            JoinOperator::ON,
            JoinType::INNER
        );

        $newBuilder = $this->builder
            ->withSet($value)
            ->withJoin($join);

        $query = (string) $newBuilder;

        self::assertStringContainsString('INSERT INTO users', $query);
        self::assertStringContainsString('INNER JOIN other_table', $query);
    }

    public function testWithAddedJoinAppendsJoinClause(): void
    {
        $value = new Value('name', 'John');
        $join1 = new Join(
            'other_table',
            'users.id',
            'other_table.user_id',
            Comparison::EQUALS,
            JoinOperator::ON,
            JoinType::INNER
        );
        $join2 = new Join(
            'another_table',
            'users.id',
            'another_table.user_id',
            Comparison::EQUALS,
            JoinOperator::ON,
            JoinType::LEFT
        );

        $newBuilder = $this->builder
            ->withSet($value)
            ->withJoin($join1)
            ->withAddedJoin($join2);

        $query = (string) $newBuilder;

        self::assertStringContainsString('INSERT INTO users', $query);
        self::assertStringContainsString('INNER JOIN other_table', $query);
        self::assertStringContainsString('LEFT JOIN another_table', $query);
    }

    public function testImmutability(): void
    {
        $originalQuery = (string) $this->builder;

        $this->builder->withSet(new Value('name', 'John'));

        self::assertSame($originalQuery, (string) $this->builder);
    }

    public function testInsertWithMultipleValues(): void
    {
        $values = [
            new Value('name', 'John Doe'),
            new Value('email', 'john@example.com'),
            new Value('age', 30),
            new Value('active', true),
        ];

        $newBuilder = $this->builder->withSet(...$values);

        $query = (string) $newBuilder;

        self::assertStringContainsString('INSERT INTO users', $query);
        self::assertStringContainsString('name', $query);
        self::assertStringContainsString('email', $query);
        self::assertStringContainsString('age', $query);
        self::assertStringContainsString('active', $query);
        self::assertStringContainsString('VALUES', $query);
    }
}
