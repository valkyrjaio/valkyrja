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

use PDO;
use PDOStatement as Statement;
use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilderContract;
use Valkyrja\Orm\Statement\Contract\StatementContract;
use Valkyrja\Orm\Statement\PdoStatement;
use Valkyrja\Orm\Throwable\Exception\RuntimeException;
use Valkyrja\Tests\Classes\Orm\Entity\EntityIntIdClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class PdoStatementTest extends TestCase
{
    protected Statement&MockObject $pdoStatement;

    protected PdoStatement $statement;

    protected function setUp(): void
    {
        $this->pdoStatement = $this->createMock(Statement::class);
        $this->statement    = new PdoStatement($this->pdoStatement);
    }

    public function testImplementsStatementContract(): void
    {
        $this->pdoStatement->expects($this->never())->method('bindValue');

        self::assertInstanceOf(StatementContract::class, $this->statement);
    }

    public function testBindValueWithStringValue(): void
    {
        $value = new Value('name', 'John');

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindValue')
            ->with(':name', 'John', PDO::PARAM_STR)
            ->willReturn(true);

        $result = $this->statement->bindValue($value);

        self::assertTrue($result);
    }

    public function testBindValueWithIntValue(): void
    {
        $value = new Value('age', 25);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindValue')
            ->with(':age', 25, PDO::PARAM_INT)
            ->willReturn(true);

        $result = $this->statement->bindValue($value);

        self::assertTrue($result);
    }

    public function testBindValueWithBoolValue(): void
    {
        $value = new Value('active', true);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindValue')
            ->with(':active', true, PDO::PARAM_BOOL)
            ->willReturn(true);

        $result = $this->statement->bindValue($value);

        self::assertTrue($result);
    }

    public function testBindValueWithNullValue(): void
    {
        $value = new Value('deleted_at', null);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindValue')
            ->with(':deleted_at', null, PDO::PARAM_NULL)
            ->willReturn(true);

        $result = $this->statement->bindValue($value);

        self::assertTrue($result);
    }

    public function testBindValueWithArrayValue(): void
    {
        $value = new Value('ids', [1, 2, 3]);

        $this->pdoStatement
            ->expects($this->exactly(3))
            ->method('bindValue')
            ->willReturn(true);

        $result = $this->statement->bindValue($value);

        self::assertTrue($result);
    }

    public function testBindValueWithQueryBuilderReturnsTrue(): void
    {
        $queryBuilder = self::createStub(QueryBuilderContract::class);
        $value        = new Value('subquery', $queryBuilder);

        $this->pdoStatement
            ->expects($this->never())
            ->method('bindValue');

        $result = $this->statement->bindValue($value);

        self::assertTrue($result);
    }

    public function testExecute(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->statement->execute();

        self::assertTrue($result);
    }

    public function testGetColumnMeta(): void
    {
        $columnMeta = ['name' => 'id', 'native_type' => 'integer'];

        $this->pdoStatement
            ->expects($this->once())
            ->method('getColumnMeta')
            ->with(0)
            ->willReturn($columnMeta);

        $result = $this->statement->getColumnMeta(0);

        self::assertSame($columnMeta, $result);
    }

    public function testGetColumnMetaThrowsExceptionOnFailure(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('getColumnMeta')
            ->with(0)
            ->willReturn(false);

        $this->pdoStatement
            ->expects($this->once())
            ->method('errorInfo')
            ->willReturn(['00000', null, null]);

        $this->expectException(RuntimeException::class);

        $this->statement->getColumnMeta(0);
    }

    public function testFetchReturnsArray(): void
    {
        $data = ['id' => 1, 'name' => 'Test'];

        $this->pdoStatement
            ->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($data);

        $result = $this->statement->fetch();

        self::assertSame($data, $result);
    }

    public function testFetchThrowsExceptionOnFailure(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn(false);

        $this->pdoStatement
            ->expects($this->once())
            ->method('errorInfo')
            ->willReturn(['00000', null, null]);

        $this->expectException(RuntimeException::class);

        $this->statement->fetch();
    }

    public function testFetchReturnsEntity(): void
    {
        $data = ['id' => 1, 'name' => 'Test'];

        $this->pdoStatement
            ->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($data);

        $result = $this->statement->fetch(EntityIntIdClass::class);

        self::assertInstanceOf(EntityContract::class, $result);
        self::assertInstanceOf(EntityIntIdClass::class, $result);
        self::assertSame(1, $result->id);
        self::assertSame('Test', $result->name);
    }

    public function testFetchColumn(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('fetchColumn')
            ->with(0)
            ->willReturn('test_value');

        $result = $this->statement->fetchColumn(0);

        self::assertSame('test_value', $result);
    }

    public function testFetchAllReturnsArrayOfArrays(): void
    {
        $data = [
            ['id' => 1, 'name' => 'Test 1'],
            ['id' => 2, 'name' => 'Test 2'],
        ];

        $this->pdoStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($data);

        $result = $this->statement->fetchAll();

        self::assertSame($data, $result);
    }

    public function testFetchAllReturnsArrayOfEntities(): void
    {
        $data = [
            ['id' => 1, 'name' => 'Test 1'],
            ['id' => 2, 'name' => 'Test 2'],
        ];

        $this->pdoStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($data);

        $result = $this->statement->fetchAll(EntityIntIdClass::class);

        self::assertCount(2, $result);
        self::assertContainsOnlyInstancesOf(EntityIntIdClass::class, $result);
        self::assertSame(1, $result[0]->id);
        self::assertSame('Test 1', $result[0]->name);
        self::assertSame(2, $result[1]->id);
        self::assertSame('Test 2', $result[1]->name);
    }

    public function testGetCountWithIntegerCount(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['COUNT(*)' => 42]]);

        $result = $this->statement->getCount();

        self::assertSame(42, $result);
    }

    public function testGetCountWithStringCount(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['count' => '15']]);

        $result = $this->statement->getCount();

        self::assertSame(15, $result);
    }

    public function testGetCountReturnsZeroForEmptyResults(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);

        $result = $this->statement->getCount();

        self::assertSame(0, $result);
    }

    public function testGetCountThrowsExceptionForUnsupportedType(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['COUNT(*)' => ['unsupported' => 'array']]]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unsupported count results');

        $this->statement->getCount();
    }

    public function testRowCount(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('rowCount')
            ->willReturn(5);

        $result = $this->statement->rowCount();

        self::assertSame(5, $result);
    }

    public function testColumnCount(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('columnCount')
            ->willReturn(3);

        $result = $this->statement->columnCount();

        self::assertSame(3, $result);
    }

    public function testErrorCode(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('errorInfo')
            ->willReturn(['42000', null, 'Syntax error']);

        $result = $this->statement->errorCode();

        self::assertSame('42000', $result);
    }

    public function testErrorCodeReturnsDefaultOnMissingCode(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('errorInfo')
            ->willReturn([]);

        $result = $this->statement->errorCode();

        self::assertSame('00000', $result);
    }

    public function testErrorMessage(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('errorInfo')
            ->willReturn(['42000', null, 'Syntax error']);

        $result = $this->statement->errorMessage();

        self::assertSame('Syntax error', $result);
    }

    public function testErrorMessageReturnsNullOnMissingMessage(): void
    {
        $this->pdoStatement
            ->expects($this->once())
            ->method('errorInfo')
            ->willReturn(['00000', null]);

        $result = $this->statement->errorMessage();

        self::assertNull($result);
    }
}
