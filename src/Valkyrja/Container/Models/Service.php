<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container\Models;

use Valkyrja\Container\Service as ServiceContract;
use Valkyrja\Dispatcher\Models\Dispatchable;

/**
 * Class Service.
 *
 * @author Melech Mizrachi
 */
class Service implements ServiceContract
{
    use Dispatchable;
    use Serviceable;
}
