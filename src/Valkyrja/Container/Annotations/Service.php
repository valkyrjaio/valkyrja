<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container\Annotations;

use Valkyrja\Annotations\Annotatable;
use Valkyrja\Container\Service as ContainerService;
use Valkyrja\Contracts\Annotations\Annotation;

/**
 * Class Service.
 *
 * @author Melech Mizrachi
 */
class Service extends ContainerService implements Annotation
{
    use Annotatable;
}
