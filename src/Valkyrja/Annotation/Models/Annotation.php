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

namespace Valkyrja\Annotation\Models;

use Valkyrja\Annotation\Annotation as Contract;
use Valkyrja\Dispatcher\Models\Dispatchable;

/**
 * Class Annotation.
 *
 * @author Melech Mizrachi
 */
class Annotation implements Contract
{
    use Annotatable;
    use Dispatchable;
}
