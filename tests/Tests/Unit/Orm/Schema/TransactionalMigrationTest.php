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

use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Schema\Abstract\Migration;
use Valkyrja\Orm\Schema\Contract\MigrationContract;
use Valkyrja\Tests\Classes\Orm\Schema\TransactionalMigrationClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class TransactionalMigrationTest extends TestCase
{
    protected ManagerContract&MockObject $orm;

    protected TransactionalMigrationClass $migration;

    protected function setUp(): void
    {
        $this->orm       = $this->createMock(ManagerContract::class);
        $this->migration = new TransactionalMigrationClass($this->orm);
    }

    public function testImplementsMigrationContract(): void
    {
        $this->orm->expects($this->never())->method('ensureTransaction');
        $this->orm->expects($this->never())->method('commit');
        $this->orm->expects($this->never())->method('rollback');

        self::assertInstanceOf(MigrationContract::class, $this->migration);
    }

    public function testExtendsMigration(): void
    {
        $this->orm->expects($this->never())->method('ensureTransaction');
        $this->orm->expects($this->never())->method('commit');
        $this->orm->expects($this->never())->method('rollback');

        self::assertInstanceOf(Migration::class, $this->migration);
    }

    public function testRunEnsuresTransactionAndCommits(): void
    {
        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);

        $this->orm
            ->expects($this->never())
            ->method('rollback');

        $this->migration->run();

        self::assertTrue($this->migration->runMigrationCalled);
        self::assertFalse($this->migration->runFailureCalled);
    }

    public function testRunRollsBackOnException(): void
    {
        $this->migration->shouldThrowOnRun = true;

        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->never())
            ->method('commit');

        $this->orm
            ->expects($this->once())
            ->method('rollback')
            ->willReturn(true);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Run migration failed');

        try {
            $this->migration->run();
        } finally {
            self::assertTrue($this->migration->runMigrationCalled);
            self::assertTrue($this->migration->runFailureCalled);
            self::assertNotNull($this->migration->runFailureException);
            self::assertSame('Run migration failed', $this->migration->runFailureException->getMessage());
        }
    }

    public function testRollbackEnsuresTransactionAndCommits(): void
    {
        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);

        $this->orm
            ->expects($this->never())
            ->method('rollback');

        $this->migration->rollback();

        self::assertTrue($this->migration->rollbackMigrationCalled);
        self::assertFalse($this->migration->rollbackFailureCalled);
    }

    public function testRollbackRollsBackOnException(): void
    {
        $this->migration->shouldThrowOnRollback = true;

        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->never())
            ->method('commit');

        $this->orm
            ->expects($this->once())
            ->method('rollback')
            ->willReturn(true);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Rollback migration failed');

        try {
            $this->migration->rollback();
        } finally {
            self::assertTrue($this->migration->rollbackMigrationCalled);
            self::assertTrue($this->migration->rollbackFailureCalled);
            self::assertNotNull($this->migration->rollbackFailureException);
            self::assertSame('Rollback migration failed', $this->migration->rollbackFailureException->getMessage());
        }
    }

    public function testRunFailureIsCalledWithException(): void
    {
        $this->migration->shouldThrowOnRun = true;

        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

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
        self::assertSame('Run migration failed', $this->migration->runFailureException->getMessage());
    }

    public function testRunFailureIsNotCalledOnSuccess(): void
    {
        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);

        $this->orm
            ->expects($this->never())
            ->method('rollback');

        $this->migration->run();

        self::assertFalse($this->migration->runFailureCalled);
        self::assertNull($this->migration->runFailureException);
    }

    public function testRollbackFailureIsCalledWithException(): void
    {
        $this->migration->shouldThrowOnRollback = true;

        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->never())
            ->method('commit');

        $this->orm
            ->expects($this->once())
            ->method('rollback')
            ->willReturn(true);

        try {
            $this->migration->rollback();
        } catch (RuntimeException) {
            // Expected
        }

        self::assertTrue($this->migration->rollbackFailureCalled);
        self::assertNotNull($this->migration->rollbackFailureException);
        self::assertInstanceOf(RuntimeException::class, $this->migration->rollbackFailureException);
        self::assertSame('Rollback migration failed', $this->migration->rollbackFailureException->getMessage());
    }

    public function testRollbackFailureIsNotCalledOnSuccess(): void
    {
        $this->orm
            ->expects($this->once())
            ->method('ensureTransaction');

        $this->orm
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);

        $this->orm
            ->expects($this->never())
            ->method('rollback');

        $this->migration->rollback();

        self::assertFalse($this->migration->rollbackFailureCalled);
        self::assertNull($this->migration->rollbackFailureException);
    }
}
