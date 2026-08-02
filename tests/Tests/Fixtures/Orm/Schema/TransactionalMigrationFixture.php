<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Orm\Schema;

use RuntimeException;
use Throwable;
use Valkyrja\Orm\Schema\Abstract\TransactionalMigration;

/**
 * Concrete transactional migration class for testing.
 */
final class TransactionalMigrationFixture extends TransactionalMigration
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
