<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cache\Data;

use Valkyrja\Cache\Data\Contract\CacheNullConfigContract;

class CacheNullConfig implements CacheNullConfigContract
{
    /**
     * @param string $nullPrefix The prefix to prepend to every key
     */
    public function __construct(
        public readonly string $nullPrefix = '',
    ) {
    }
}
