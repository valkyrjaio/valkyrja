<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Server\Data\Contract;

interface HttpServerConfigContract
{
    /**
     * The path to write cached responses to.
     *
     * A null value tells the server to write to the framework storage cache
     * directory. The framework resolves that directory after it sets the base
     * path, so the config cannot hold it as a default.
     *
     * @var non-empty-string|null
     */
    public string|null $responseCacheFilePath {
        get;
    }
}
