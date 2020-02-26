<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Cache;

use InvalidArgumentException;

/**
 * Interface Cache.
 *
 * @author Melech Mizrachi
 */
interface Cache
{
    /**
     * Get a store by name.
     *
     * @param string|null $name
     *
     * @throws InvalidArgumentException If the name doesn't exist
     *
     * @return Store
     */
    public function getStore(string $name = null): Store;
}
