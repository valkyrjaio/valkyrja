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

namespace Valkyrja\Container\Annotation;

use Valkyrja\Annotation\Annotations;
use Valkyrja\Container\Annotation\Models\Service;
use Valkyrja\Container\Annotation\Models\ServiceAlias;
use Valkyrja\Container\Annotation\Models\ServiceContext;

/**
 * Interface ContainerAnnotations.
 *
 * @author Melech Mizrachi
 */
interface ContainerAnnotations extends Annotations
{
    /**
     * Get the services.
     *
     * @param string ...$classes The classes
     *
     * @return Service[]
     */
    public function getServices(string ...$classes): array;

    /**
     * Get the alias services.
     *
     * @param string ...$classes The classes
     *
     * @return ServiceAlias[]
     */
    public function getAliasServices(string ...$classes): array;

    /**
     * Get the context services.
     *
     * @param string ...$classes The classes
     *
     * @return ServiceContext[]
     */
    public function getContextServices(string ...$classes): array;
}
