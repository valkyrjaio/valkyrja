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
