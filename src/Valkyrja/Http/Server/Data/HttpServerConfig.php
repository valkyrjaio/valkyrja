<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Server\Data;

use Valkyrja\Http\Server\Data\Contract\HttpServerConfigContract;

class HttpServerConfig implements HttpServerConfigContract
{
    /**
     * @param non-empty-string|null $responseCacheFilePath The path to write cached responses to
     */
    public function __construct(
        public readonly string|null $responseCacheFilePath = null,
    ) {
    }
}
