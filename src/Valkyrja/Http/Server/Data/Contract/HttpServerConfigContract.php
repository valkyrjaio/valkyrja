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
