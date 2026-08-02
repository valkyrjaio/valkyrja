<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Session\Data;

use Valkyrja\Session\Data\Contract\SessionTokenConfigContract;

class SessionTokenConfig implements SessionTokenConfigContract
{
    /**
     * @param non-empty-string|null $tokenOptionName The cli option that carries the token
     * @param non-empty-string|null $tokenHeaderName The http header that carries the token
     */
    public function __construct(
        public readonly string|null $tokenOptionName = null,
        public readonly string|null $tokenHeaderName = null,
    ) {
    }
}
