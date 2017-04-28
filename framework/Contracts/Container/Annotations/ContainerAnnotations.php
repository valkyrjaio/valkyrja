<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Container\Annotations;

use Valkyrja\Contracts\Annotations\Annotations;

/**
 * Interface ContainerAnnotations
 *
 * @package Valkyrja\Contracts\Container\Annotations
 *
 * @author  Melech Mizrachi
 */
interface ContainerAnnotations extends Annotations
{
    /**
     * Get the services.
     *
     * @param string[] $classes The classes
     *
     * @return \Valkyrja\Container\Service[]
     */
    public function getServices(string ...$classes): array;

    /**
     * Get the context services.
     *
     * @param string[] $classes The classes
     *
     * @return \Valkyrja\Container\ContextService[]
     */
    public function getContextServices(string ...$classes): array;
}
