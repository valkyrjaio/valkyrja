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

namespace Valkyrja\Annotation\Models;

use Valkyrja\Annotation\Annotation as Contract;
use Valkyrja\Dispatcher\Models\Dispatch;

/**
 * Class Annotation.
 *
 * @author Melech Mizrachi
 */
class Annotation extends Dispatch implements Contract
{
    use Annotatable;
}
