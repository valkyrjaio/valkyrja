<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Server\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Http\Server\Data\Contract\HttpServerConfigContract;

final class HttpServerConfigFixture extends Config implements HttpServerConfigContract
{
    /**
     * @param non-empty-string|null $responseCacheFilePath
     */
    public function __construct(
        public string|null $responseCacheFilePath = '/tmp/response-cache',
    ) {
        parent::__construct();
    }
}
