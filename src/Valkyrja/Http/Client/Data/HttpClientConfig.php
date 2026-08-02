<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Client\Data;

use Valkyrja\Http\Client\Data\Contract\HttpClientConfigContract;
use Valkyrja\Http\Client\Manager\Contract\ClientContract;
use Valkyrja\Http\Client\Manager\GuzzleClient;

class HttpClientConfig implements HttpClientConfigContract
{
    /**
     * @param class-string<ClientContract> $defaultClient The client to use by default
     */
    public function __construct(
        public readonly string $defaultClient = GuzzleClient::class,
    ) {
    }
}
