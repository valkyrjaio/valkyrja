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

namespace Valkyrja\Console\Models;

use Valkyrja\Console\Command as CommandContract;
use Valkyrja\Dispatcher\Models\Dispatchable;

/**
 * Class Command.
 *
 * @author Melech Mizrachi
 *
 * @method static fromArray(array $properties)
 */
class Command implements CommandContract
{
    use Commandable;
    use Dispatchable;
}
