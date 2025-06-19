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

namespace Valkyrja\Container\Attribute\Contract;

use Valkyrja\Container\Attribute\Alias;
use Valkyrja\Container\Attribute\Context;
use Valkyrja\Container\Attribute\Service;

/**
 * Interface Collector.
 *
 * @author Melech Mizrachi
 */
interface Collector
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
     * Get the aliases.
     *
     * @param class-string ...$classes The classes
     *
     * @return Alias[]
     */
    public function getAliases(string ...$classes): array;

    /**
     * Get the context services.
     *
     * @param class-string ...$classes The classes
     *
     * @return Context[]
     */
    public function getContextServices(string ...$classes): array;
}
