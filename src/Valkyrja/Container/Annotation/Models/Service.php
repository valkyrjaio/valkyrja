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
use Valkyrja\Container\Annotation\Service as ServiceContract;
use Valkyrja\Container\Models\Serviceable;

/**
 * Class Service.
 *
 * @author Melech Mizrachi
 */
class Service extends Annotation implements ServiceContract
{
    use Serviceable;
}
