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

namespace Valkyrja\Tests\Unit\Orm\Statement;

use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Statement\Contract\StatementContract;
use Valkyrja\Orm\Statement\NullStatement;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class NullStatementTest extends TestCase
{
    protected NullStatement $statement;

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
        self::assertSame([], $this->statement->fetch('SomeEntity'));
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
        self::assertSame([], $this->statement->fetchAll('SomeEntity'));
    }

    public function testGetCountReturnsZero(): void
    {
        self::assertSame(0, $this->statement->getCount());
    }

    public function testRowCountReturnsZero(): void
    {
        self::assertSame(0, $this->statement->rowCount());
    }

    public function testColumnCountReturnsZero(): void
    {
        self::assertSame(0, $this->statement->columnCount());
    }

    public function testErrorCodeReturnsSuccessCode(): void
    {
        self::assertSame('00000', $this->statement->errorCode());
    }

    public function testErrorMessageReturnsNull(): void
    {
        self::assertNull($this->statement->errorMessage());
    }
}
