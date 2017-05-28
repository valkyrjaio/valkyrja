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
use Valkyrja\Container\ServiceContext as ContainerServiceContext;
use Valkyrja\Contracts\Annotations\Annotation;

/**
 * Class ServiceContext.
 *
 * @author Melech Mizrachi
 */
class ServiceContext extends ContainerServiceContext implements Annotation
{
    use Annotatable;
}
