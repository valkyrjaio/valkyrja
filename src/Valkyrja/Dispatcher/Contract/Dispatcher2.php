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

namespace Valkyrja\Dispatcher\Contract;

use Valkyrja\Dispatcher\Data\Contract\Dispatch;

/**
 * Interface Dispatcher.
 *
 * @author Melech Mizrachi
 */
interface Dispatcher2
{
    /**
     * Dispatch a callable.
     *
     * @param Dispatch                     $dispatch  The dispatch
     * @param array<array-key, mixed>|null $arguments The arguments
     *
     * @return mixed
     */
    public function dispatch(Dispatch $dispatch, array|null $arguments = null): mixed;
}
