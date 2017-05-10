<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Routing\Annotations;

use Valkyrja\Contracts\Annotations\Annotations;

/**
 * Interface RouteAnnotations.
 *
 *
 * @author  Melech Mizrachi
 */
interface RouteAnnotations extends Annotations
{
    /**
     * Get routes.
     *
     * @param string[] $classes The classes
     *
     * @return array
     */
    public function getRoutes(string ...$classes): array;
}
