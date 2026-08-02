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

use Valkyrja\Session\Data\Contract\SessionJwtConfigContract;

class SessionJwtConfig implements SessionJwtConfigContract
{
    /**
     * @param non-empty-string|null $jwtOptionName The cli option that carries the token
     * @param non-empty-string|null $jwtHeaderName The http header that carries the token
     */
    public function __construct(
        public readonly string|null $jwtOptionName = null,
        public readonly string|null $jwtHeaderName = null,
    ) {
    }
}
