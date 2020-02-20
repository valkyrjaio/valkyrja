<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Dispatcher\Models;

use Valkyrja\Dispatcher\Dispatch as DispatchContract;

/**
 * Class Dispatch.
 *
 * @author Melech Mizrachi
 */
class Dispatch implements DispatchContract
{
    use Dispatchable;
}
