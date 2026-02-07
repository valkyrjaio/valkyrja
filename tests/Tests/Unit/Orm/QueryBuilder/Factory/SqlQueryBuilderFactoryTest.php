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

namespace Valkyrja\Tests\Unit\Orm\QueryBuilder\Factory;

use Valkyrja\Orm\QueryBuilder\Contract\DeleteQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\InsertQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\SelectQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\UpdateQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactoryContract;
use Valkyrja\Orm\QueryBuilder\Factory\SqlQueryBuilderFactory;
use Valkyrja\Orm\QueryBuilder\SqlDeleteQueryBuilder;
use Valkyrja\Orm\QueryBuilder\SqlInsertQueryBuilder;
use Valkyrja\Orm\QueryBuilder\SqlSelectQueryBuilder;
use Valkyrja\Orm\QueryBuilder\SqlUpdateQueryBuilder;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class SqlQueryBuilderFactoryTest extends TestCase
{
    protected SqlQueryBuilderFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new SqlQueryBuilderFactory();
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(QueryBuilderFactoryContract::class, $this->factory);
    }

    public function testSelectReturnsSelectQueryBuilder(): void
    {
        $builder = $this->factory->select('users');

        self::assertInstanceOf(SelectQueryBuilderContract::class, $builder);
        self::assertInstanceOf(SqlSelectQueryBuilder::class, $builder);
    }

    public function testSelectBuilderGeneratesCorrectQuery(): void
    {
        $builder = $this->factory->select('users');

        self::assertStringContainsString('SELECT', (string) $builder);
        self::assertStringContainsString('FROM users', (string) $builder);
    }

    public function testInsertReturnsInsertQueryBuilder(): void
    {
        $builder = $this->factory->insert('users');

        self::assertInstanceOf(InsertQueryBuilderContract::class, $builder);
        self::assertInstanceOf(SqlInsertQueryBuilder::class, $builder);
    }

    public function testInsertBuilderGeneratesCorrectQuery(): void
    {
        $builder = $this->factory->insert('users');

        self::assertStringContainsString('INSERT', (string) $builder);
        self::assertStringContainsString('INTO users', (string) $builder);
    }

    public function testUpdateReturnsUpdateQueryBuilder(): void
    {
        $builder = $this->factory->update('users');

        self::assertInstanceOf(UpdateQueryBuilderContract::class, $builder);
        self::assertInstanceOf(SqlUpdateQueryBuilder::class, $builder);
    }

    public function testUpdateBuilderGeneratesCorrectQuery(): void
    {
        $builder = $this->factory->update('users');

        self::assertStringContainsString('UPDATE users', (string) $builder);
    }

    public function testDeleteReturnsDeleteQueryBuilder(): void
    {
        $builder = $this->factory->delete('users');

        self::assertInstanceOf(DeleteQueryBuilderContract::class, $builder);
        self::assertInstanceOf(SqlDeleteQueryBuilder::class, $builder);
    }

    public function testDeleteBuilderGeneratesCorrectQuery(): void
    {
        $builder = $this->factory->delete('users');

        self::assertStringContainsString('DELETE', (string) $builder);
        self::assertStringContainsString('FROM users', (string) $builder);
    }

    public function testFactoryCreatesNewInstancesEachTime(): void
    {
        $builder1 = $this->factory->select('users');
        $builder2 = $this->factory->select('users');

        self::assertNotSame($builder1, $builder2);
    }
}
