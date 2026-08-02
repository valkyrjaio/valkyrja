<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Schema\Abstract;

use Override;
use Throwable;

abstract class TransactionalMigration extends Migration
{
    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    #[Override]
    public function run(): void
    {
        $orm = $this->orm;

        try {
            $orm->ensureTransaction();

            $this->runMigration();

            $orm->commit();
        } catch (Throwable $exception) {
            $orm->rollback();

            $this->runFailure($exception);

            throw $exception;
        }
    }

    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    #[Override]
    public function rollback(): void
    {
        $orm = $this->orm;

        try {
            $orm->ensureTransaction();

            $this->rollbackMigration();

            $orm->commit();
        } catch (Throwable $exception) {
            $orm->rollback();

            $this->rollbackFailure($exception);

            throw $exception;
        }
    }

    /**
     * Do on run failure.
     *
     * @param Throwable $exception The exception
     */
    protected function runFailure(Throwable $exception): void
    {
    }

    /**
     * Do on rollback failure.
     *
     * @param Throwable $exception The exception
     */
    protected function rollbackFailure(Throwable $exception): void
    {
    }

    /**
     * Run the migration.
     */
    abstract protected function runMigration(): void;

    /**
     * Rollback the migration.
     */
    abstract protected function rollbackMigration(): void;
}
