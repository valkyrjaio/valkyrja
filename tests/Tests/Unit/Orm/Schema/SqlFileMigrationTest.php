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

namespace Valkyrja\Tests\Unit\Orm\Schema;

use Override;
use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Schema\Abstract\TransactionalMigration;
use Valkyrja\Orm\Schema\Contract\MigrationContract;
use Valkyrja\Orm\Statement\Contract\StatementContract;
use Valkyrja\Orm\Throwable\Exception\RuntimeException;
use Valkyrja\Tests\Classes\Orm\Schema\SqlFileMigrationClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function file_put_contents;
use function sys_get_temp_dir;
use function tempnam;
use function unlink;

final class SqlFileMigrationTest extends TestCase
{
    protected ManagerContract&MockObject $orm;

    protected SqlFileMigrationClass $migration;

    protected string $runSqlFile;

    protected string $rollbackSqlFile;

    protected function setUp(): void
    {
        $this->orm       = $this->createMock(ManagerContract::class);
        $this->migration = new SqlFileMigrationClass($this->orm);

        // Create temporary SQL files
        $this->runSqlFile      = tempnam(sys_get_temp_dir(), 'run_migration_') . '.sql';
        $this->rollbackSqlFile = tempnam(sys_get_temp_dir(), 'rollback_migration_') . '.sql';

        $this->migration->runFilePath      = $this->runSqlFile;
        $this->migration->rollbackFilePath = $this->rollbackSqlFile;
    }

    #[Override]
    protected function tearDown(): void
    {
        // Clean up temporary files
        if (file_exists($this->runSqlFile)) {
            unlink($this->runSqlFile);
        }

        if (file_exists($this->rollbackSqlFile)) {
            unlink($this->rollbackSqlFile);
        }
    }

    public function testImplementsMigrationContract(): void
    {
        $this->orm->expects($this->never())->method('ensureTransaction');
        $this->orm->expects($this->never())->method('commit');
        $this->orm->expects($this->never())->method('rollback');
        $this->orm->expects($this->never())->method('prepare');

        self::assertInstanceOf(MigrationContract::class, $this->migration);
    }

    public function testExtendsTransactionalMigration(): void
    {
        $this->orm->expects($this->never())->method('ensureTransaction');
        $this->orm->expects($this->never())->method('commit');
        $this->orm->expects($this->never())->method('rollback');
        $this->orm->expects($this->never())->method('prepare');

        self::assertInstanceOf(TransactionalMigration::class, $this->migration);
    }

    public function testRunExecutesSqlFromFile(): void
    {
        file_put_contents($this->runSqlFile, 'CREATE TABLE users (id INT); CREATE TABLE posts (id INT)');

        $statement = $this->createMock(StatementContract::class);
        $statement
            ->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($statement);

        $this->orm
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);

        $this->orm
            ->expects($this->never())
            ->method('rollback');

        $this->migration->run();
    }

    public function testRunSkipsEmptyStatements(): void
    {
        file_put_contents($this->runSqlFile, 'CREATE TABLE users (id INT);;; CREATE TABLE posts (id INT);');

        $statement = $this->createMock(StatementContract::class);
        $statement
            ->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($statement);

        $this->orm
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);

        $this->migration->run();
    }

    public function testRunThrowsExceptionForInvalidFile(): void
    {
        $this->migration->runFilePath = '/nonexistent/file.sql';

        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->never())
            ->method('prepare');

        $this->orm
            ->expects($this->never())
            ->method('commit');

        $this->orm
            ->expects($this->once())
            ->method('rollback')
            ->willReturn(true);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid file /nonexistent/file.sql given');

        @$this->migration->run();
    }

    public function testRunThrowsExceptionWhenStatementFails(): void
    {
        file_put_contents($this->runSqlFile, 'INVALID SQL STATEMENT');

        $statement = $this->createMock(StatementContract::class);
        $statement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(false);

        $statement
            ->expects($this->once())
            ->method('errorMessage')
            ->willReturn('Syntax error');

        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement);

        $this->orm
            ->expects($this->never())
            ->method('commit');

        $this->orm
            ->expects($this->once())
            ->method('rollback')
            ->willReturn(true);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Syntax error');

        $this->migration->run();
    }

    public function testRunThrowsDefaultErrorWhenStatementFailsWithNoMessage(): void
    {
        file_put_contents($this->runSqlFile, 'INVALID SQL STATEMENT');

        $statement = $this->createMock(StatementContract::class);
        $statement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(false);

        $statement
            ->expects($this->once())
            ->method('errorMessage')
            ->willReturn(null);

        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement);

        $this->orm
            ->expects($this->never())
            ->method('commit');

        $this->orm
            ->expects($this->once())
            ->method('rollback')
            ->willReturn(true);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Error occurred');

        $this->migration->run();
    }

    public function testRollbackExecutesSqlFromFile(): void
    {
        file_put_contents($this->rollbackSqlFile, 'DROP TABLE users');

        $statement = $this->createMock(StatementContract::class);
        $statement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->once())
            ->method('prepare')
            ->with('DROP TABLE users')
            ->willReturn($statement);

        $this->orm
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);

        $this->orm
            ->expects($this->never())
            ->method('rollback');

        $this->migration->rollback();
    }

    public function testRunCallsRunFailureOnException(): void
    {
        $this->migration->runFilePath = '/nonexistent/file.sql';

        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->never())
            ->method('prepare');

        $this->orm
            ->expects($this->never())
            ->method('commit');

        $this->orm
            ->expects($this->once())
            ->method('rollback')
            ->willReturn(true);

        try {
            @$this->migration->run();
        } catch (RuntimeException) {
            // Expected
        }

        self::assertTrue($this->migration->runFailureCalled);
        self::assertNotNull($this->migration->runFailureException);
        self::assertInstanceOf(RuntimeException::class, $this->migration->runFailureException);
        self::assertStringContainsString('Invalid file', $this->migration->runFailureException->getMessage());
    }

    public function testRunFailureIsCalledWhenStatementExecutionFails(): void
    {
        file_put_contents($this->runSqlFile, 'INVALID SQL STATEMENT');

        $statement = $this->createMock(StatementContract::class);
        $statement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(false);

        $statement
            ->expects($this->once())
            ->method('errorMessage')
            ->willReturn('SQL syntax error');

        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement);

        $this->orm
            ->expects($this->never())
            ->method('commit');

        $this->orm
            ->expects($this->once())
            ->method('rollback')
            ->willReturn(true);

        try {
            $this->migration->run();
        } catch (RuntimeException) {
            // Expected
        }

        self::assertTrue($this->migration->runFailureCalled);
        self::assertNotNull($this->migration->runFailureException);
        self::assertInstanceOf(RuntimeException::class, $this->migration->runFailureException);
        self::assertSame('SQL syntax error', $this->migration->runFailureException->getMessage());
    }
}
