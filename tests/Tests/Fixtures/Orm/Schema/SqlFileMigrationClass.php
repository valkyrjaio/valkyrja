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

use Throwable;
use Valkyrja\Orm\Schema\Abstract\SqlFileMigration;

/**
 * Concrete SQL file migration class for testing.
 */
final class SqlFileMigrationClass extends SqlFileMigration
{
    public string $runFilePath      = '';
    public string $rollbackFilePath = '';
    public bool $runFailureCalled   = false;

    public Throwable|null $runFailureException = null;

    /**
     * @inheritDoc
     */
    protected function getRunMigrationFilePath(): string
    {
        return $this->runFilePath;
    }

    /**
     * @inheritDoc
     */
    protected function getRollbackMigrationFilePath(): string
    {
        return $this->rollbackFilePath;
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
}
