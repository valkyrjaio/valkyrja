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

namespace Valkyrja\Orm\Schema;

use Override;
use Valkyrja\Orm\Contract\Manager;
use Valkyrja\Orm\Schema\Contract\Migration as Contract;

/**
 * Abstract Class Migration.
 *
 * @author Melech Mizrachi
 */
abstract class Migration implements Contract
{
    /**
     * Migration constructor.
     */
    public function __construct(
        protected Manager $orm
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
