<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Orm\Statement;

use Override;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Statement\Contract\StatementContract;
use Valkyrja\Orm\Statement\NullStatement;
use Valkyrja\Tests\Fixtures\Orm\Entity\EntityFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class NullStatementTest extends TestCase
{
    protected NullStatement $statement;

    #[Override]
    protected function setUp(): void
    {
        $this->statement = new NullStatement();
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(StatementContract::class, $this->statement);
    }

    public function testBindValueReturnsTrue(): void
    {
        $value = new Value('column', 'test');

        self::assertTrue($this->statement->bindValue($value));
    }

    public function testExecuteReturnsTrue(): void
    {
        self::assertTrue($this->statement->execute());
    }

    public function testGetColumnMetaReturnsEmptyArray(): void
    {
        self::assertSame([], $this->statement->getColumnMeta(0));
    }

    public function testFetchReturnsEmptyArray(): void
    {
        self::assertSame([], $this->statement->fetch());
    }

    public function testFetchWithEntityReturnsEmptyArray(): void
    {
        self::assertInstanceOf(EntityFixture::class, $this->statement->fetchEntity(EntityFixture::class));
    }

    public function testFetchColumnReturnsNull(): void
    {
        self::assertNull($this->statement->fetchColumn());
    }

    public function testFetchColumnWithColumnNumberReturnsNull(): void
    {
        self::assertNull($this->statement->fetchColumn(1));
    }

    public function testFetchAllReturnsEmptyArray(): void
    {
        self::assertSame([], $this->statement->fetchAll());
    }

    public function testFetchAllWithEntityReturnsEmptyArray(): void
    {
        self::assertSame([], $this->statement->fetchAllEntities('SomeEntity'));
    }

    public function testGetCountReturnsZero(): void
    {
        self::assertSame(0, $this->statement->getCount());
    }

    public function testRowCountReturnsZero(): void
    {
        self::assertSame(0, $this->statement->getRowCount());
    }

    public function testColumnCountReturnsZero(): void
    {
        self::assertSame(0, $this->statement->getColumnCount());
    }

    public function testHasErrorReturnsFalse(): void
    {
        self::assertFalse($this->statement->hasError());
    }

    public function testErrorCodeReturnsSuccessCode(): void
    {
        self::assertSame('00000', $this->statement->getErrorCode());
    }

    public function testErrorMessageReturnsNull(): void
    {
        self::assertSame('', $this->statement->getErrorMessage());
    }
}
