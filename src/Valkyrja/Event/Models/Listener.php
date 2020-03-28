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

namespace Valkyrja\Event\Models;

use Valkyrja\Dispatcher\Models\Dispatchable;
use Valkyrja\Event\Listener as ListenerContract;

/**
 * Class Listener.
 *
 * @author Melech Mizrachi
 */
class Listener implements ListenerContract
{
    use Dispatchable;
    use Listenable;
}
