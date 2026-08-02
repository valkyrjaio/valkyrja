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

use Throwable;
use Valkyrja\Orm\Schema\Abstract\SqlFileMigration;

/**
 * Concrete SQL file migration class for testing.
 */
final class SqlFileMigrationFixture extends SqlFileMigration
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
