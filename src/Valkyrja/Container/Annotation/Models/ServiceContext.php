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

namespace Valkyrja\Container\Annotation\Models;

use Valkyrja\Annotation\Models\Annotation;
use Valkyrja\Container\Annotation\ServiceContext as ServiceContextContract;
use Valkyrja\Container\Models\Serviceable;
use Valkyrja\Container\Models\ServiceContextable;

/**
 * Class ServiceContext.
 *
 * @author Melech Mizrachi
 */
class ServiceContext extends Annotation implements ServiceContextContract
{
    use Serviceable;
    use ServiceContextable;
}