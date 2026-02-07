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

namespace Valkyrja\Tests\Classes\Orm\Schema;

use RuntimeException;
use Throwable;
use Valkyrja\Orm\Schema\Abstract\TransactionalMigration;

/**
 * Concrete transactional migration class for testing.
 */
class TransactionalMigrationClass extends TransactionalMigration
{
    public bool $runMigrationCalled      = false;
    public bool $rollbackMigrationCalled = false;
    public bool $runFailureCalled        = false;
    public bool $rollbackFailureCalled   = false;
    public bool $shouldThrowOnRun        = false;
    public bool $shouldThrowOnRollback   = false;

    public Throwable|null $runFailureException      = null;
    public Throwable|null $rollbackFailureException = null;

    /**
     * @inheritDoc
     */
    protected function runMigration(): void
    {
        $this->runMigrationCalled = true;

        if ($this->shouldThrowOnRun) {
            throw new RuntimeException('Run migration failed');
        }
    }

    /**
     * @inheritDoc
     */
    protected function rollbackMigration(): void
    {
        $this->rollbackMigrationCalled = true;

        if ($this->shouldThrowOnRollback) {
            throw new RuntimeException('Rollback migration failed');
        }
    }

    /**
     * @inheritDoc
     */
    protected function runFailure(Throwable $exception): void
    {
        parent::runFailure($exception);

        $this->runFailureCalled    = true;
        $this->runFailureException = $exception;
    }

    /**
     * @inheritDoc
     */
    protected function rollbackFailure(Throwable $exception): void
    {
        parent::rollbackFailure($exception);

        $this->rollbackFailureCalled    = true;
        $this->rollbackFailureException = $exception;
    }
}
