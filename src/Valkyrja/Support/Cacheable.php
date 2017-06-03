<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Support;

/**
 * Interface Cacheable.
 *
 * @author Melech Mizrachi
 */
interface Cacheable
{
    /**
     * Set the data from cache.
     *
     * @return void
     */
    public function setup(): void;

    /**
     * Get a cacheable representation of the data.
     *
     * @return array
     */
    public function getCacheable(): array;
}
