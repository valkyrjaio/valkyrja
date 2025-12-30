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

namespace Valkyrja\Orm\Schema\Abstract;

use Override;
use Throwable;

/**
 * Abstract Class TransactionalMigration.
 *
 * @author Melech Mizrachi
 */
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
     *
     * @return void
     */
    protected function runFailure(Throwable $exception): void
    {
    }

    /**
     * Do on rollback failure.
     *
     * @param Throwable $exception The exception
     *
     * @return void
     */
    protected function rollbackFailure(Throwable $exception): void
    {
    }

    /**
     * Run the migration.
     *
     * @return void
     */
    abstract protected function runMigration(): void;

    /**
     * Rollback the migration.
     *
     * @return void
     */
    abstract protected function rollbackMigration(): void;
}
