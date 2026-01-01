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
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Schema\Contract\MigrationContract as Contract;

/**
 * Abstract Class Migration.
 */
abstract class Migration implements Contract
{
    /**
     * Migration constructor.
     */
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
