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

namespace Valkyrja\Dispatch\Dispatcher\Contract;

use Valkyrja\Dispatch\Data\Contract\DispatchContract;

interface DispatcherContract
{
    /**
     * Dispatch a callable.
     *
     * @param DispatchContract                    $dispatch  The dispatch
     * @param array<non-empty-string, mixed>|null $arguments The arguments
     */
    public function dispatch(DispatchContract $dispatch, array|null $arguments = null): mixed;
}
