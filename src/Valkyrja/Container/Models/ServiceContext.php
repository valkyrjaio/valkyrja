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

namespace Valkyrja\Container\Models;

use Valkyrja\Container\ServiceContext as ServiceContextContract;
use Valkyrja\Dispatcher\Models\Dispatchable;

/**
 * Class ServiceContext.
 *
 * @author Melech Mizrachi
 */
class ServiceContext implements ServiceContextContract
{
    use Dispatchable;
    use Serviceable;
    use ServiceContextable;
}
