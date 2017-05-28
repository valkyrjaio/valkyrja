<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Events\Annotations;

use Valkyrja\Annotations\Annotatable;
use Valkyrja\Contracts\Annotations\Annotation;
use Valkyrja\Events\Listener as EventListener;

/**
 * Class Event.
 *
 * @author Melech Mizrachi
 */
class Listener extends EventListener implements Annotation
{
    use Annotatable;
}
