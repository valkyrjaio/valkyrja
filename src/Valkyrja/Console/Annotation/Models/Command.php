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

namespace Valkyrja\Console\Annotation\Models;

use Valkyrja\Annotation\Models\Annotation;
use Valkyrja\Console\Annotation\Command as Contract;
use Valkyrja\Console\Models\Commandable;

/**
 * Class Command.
 *
 * @author Melech Mizrachi
 */
class Command extends Annotation implements Contract
{
    use Commandable;
}
