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

namespace Valkyrja\Container\Annotation\Contract;

use Valkyrja\Container\Annotation\Service;
use Valkyrja\Container\Annotation\Service\Alias;
use Valkyrja\Container\Annotation\Service\Context;

/**
 * Interface Annotations.
 *
 * @author Melech Mizrachi
 */
interface Annotations
{
    /**
     * Get the services.
     *
     * @param class-string ...$classes The classes
     *
     * @return Service[]
     */
    public function getServices(string ...$classes): array;

    /**
     * Get the alias services.
     *
     * @param class-string ...$classes The classes
     *
     * @return Alias[]
     */
    public function getAliasServices(string ...$classes): array;

    /**
     * Get the context services.
     *
     * @param class-string ...$classes The classes
     *
     * @return Context[]
     */
    public function getContextServices(string ...$classes): array;
}
