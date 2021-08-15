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

namespace Valkyrja\Container;

use Valkyrja\Container\Annotations\Service\Alias;
use Valkyrja\Container\Annotations\Service\Context;
use Valkyrja\Container\Annotations\Service;

/**
 * Interface Annotator.
 *
 * @author Melech Mizrachi
 */
interface Annotator
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
     * @return Alias[]
     */
    public function getAliasServices(string ...$classes): array;

    /**
     * Get the context services.
     *
     * @param string ...$classes The classes
     *
     * @return Context[]
     */
    public function getContextServices(string ...$classes): array;
}
