<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Annotations;

use Valkyrja\Support\Collection;

use mindplay\annotations\IAnnotation;

/**
 * Class Route
 *
 * @package Valkyrja\Routing\Annotations
 *
 * @author  Melech Mizrachi
 *
 * @usage('class' => true, 'method' => true, 'multiple' => true, 'inherited' => true)
 */
class Route extends Collection implements IAnnotation
{
    /**
     * Initialize annotations.
     *
     * @param array $properties
     */
    public function initAnnotation(array $properties)
    {
        foreach ($properties as $key => $property) {
            $this->set($key, $property);
        }
    }
}
