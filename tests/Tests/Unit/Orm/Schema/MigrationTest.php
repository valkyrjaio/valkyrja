<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Orm\Schema;

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Schema\Contract\MigrationContract;
use Valkyrja\Tests\Fixtures\Orm\Schema\MigrationFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class MigrationTest extends TestCase
{
    protected ManagerContract&MockObject $orm;

    protected MigrationFixture $migration;

    protected function setUp(): void
    {
        $this->orm       = $this->createMock(ManagerContract::class);
        $this->migration = new MigrationFixture($this->orm);
    }

    public function testImplementsMigrationContract(): void
    {
        $this->orm->expects($this->never())->method('ensureTransaction');
        $this->orm->expects($this->never())->method('commit');
        $this->orm->expects($this->never())->method('rollback');

        self::assertInstanceOf(MigrationContract::class, $this->migration);
    }

    public function testRunCallsRunMethod(): void
    {
        $this->orm->expects($this->never())->method('ensureTransaction');
        $this->orm->expects($this->never())->method('commit');
        $this->orm->expects($this->never())->method('rollback');

        self::assertFalse($this->migration->runCalled);

        $this->migration->run();

        self::assertTrue($this->migration->runCalled);
    }

    public function testRollbackCallsRollbackMethod(): void
    {
        $this->orm->expects($this->never())->method('ensureTransaction');
        $this->orm->expects($this->never())->method('commit');
        $this->orm->expects($this->never())->method('rollback');

        self::assertFalse($this->migration->rollbackCalled);

        $this->migration->rollback();

        self::assertTrue($this->migration->rollbackCalled);
    }
}
