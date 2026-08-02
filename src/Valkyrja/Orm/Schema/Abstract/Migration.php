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
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Schema\Contract\MigrationContract;

abstract class Migration implements MigrationContract
{
    public function __construct(
        protected ManagerContract $orm
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    abstract public function run(): void;

    /**
     * @inheritDoc
     */
    #[Override]
    abstract public function rollback(): void;
}
