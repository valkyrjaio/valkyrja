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

namespace Valkyrja\Container\Annotation\Models;

use Valkyrja\Annotation\Models\Annotation;
use Valkyrja\Container\Annotation\Service\Context as Contract;
use Valkyrja\Container\Models\Serviceable;
use Valkyrja\Container\Models\ServiceContextable;

/**
 * Class ServiceContext.
 *
 * @author Melech Mizrachi
 */
class Context extends Annotation implements Contract
{
    use Serviceable;
    use ServiceContextable;
}
