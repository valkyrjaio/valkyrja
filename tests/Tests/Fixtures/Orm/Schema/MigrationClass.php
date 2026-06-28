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

use Valkyrja\Orm\Schema\Abstract\Migration;

/**
 * Concrete migration class for testing.
 */
final class MigrationClass extends Migration
{
    public bool $runCalled      = false;
    public bool $rollbackCalled = false;

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        $this->runCalled = true;
    }

    /**
     * @inheritDoc
     */
    public function rollback(): void
    {
        $this->rollbackCalled = true;
    }
}
