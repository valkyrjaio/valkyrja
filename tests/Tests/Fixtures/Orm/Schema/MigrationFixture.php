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

use Override;
use Valkyrja\Orm\Schema\Abstract\Migration;

/**
 * Concrete migration class for testing.
 */
final class MigrationFixture extends Migration
{
    public bool $runCalled      = false;
    public bool $rollbackCalled = false;

    /**
     * @inheritDoc
     */
    #[Override]
    public function run(): void
    {
        $this->runCalled = true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function rollback(): void
    {
        $this->rollbackCalled = true;
    }
}
