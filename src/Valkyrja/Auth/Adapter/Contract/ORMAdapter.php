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

namespace Valkyrja\Auth\Adapter\Contract;

use Valkyrja\Orm\Contract\Manager;

/**
 * Interface ORMAdapter.
 *
 * @author Melech Mizrachi
 */
interface ORMAdapter extends Adapter
{
    /**
     * Get the ORM service.
     *
     * @return Manager
     */
    public function getOrm(): Manager;
}
