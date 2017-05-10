<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Annotations;

use Valkyrja\Contracts\Annotations\Annotation as AnnotationContract;

/**
 * Class Annotation.
 *
 *
 * @author  Melech Mizrachi
 */
class Annotation implements AnnotationContract
{
    use Annotatable;
}
