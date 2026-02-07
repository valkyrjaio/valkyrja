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
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Schema\Contract\MigrationContract;
use Valkyrja\Tests\Classes\Orm\Schema\MigrationClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class MigrationTest extends TestCase
{
    protected ManagerContract&MockObject $orm;

    protected MigrationClass $migration;

    protected function setUp(): void
    {
        $this->orm       = $this->createMock(ManagerContract::class);
        $this->migration = new MigrationClass($this->orm);
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
