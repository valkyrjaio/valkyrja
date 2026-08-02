<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Client\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Http\Client\Data\Contract\HttpClientConfigContract;
use Valkyrja\Http\Client\Manager\Contract\ClientContract;
use Valkyrja\Http\Client\Manager\NullClient;

final class HttpClientConfigFixture extends Config implements HttpClientConfigContract
{
    /**
     * @param class-string<ClientContract> $defaultClient
     */
    public function __construct(
        public string $defaultClient = NullClient::class,
    ) {
        parent::__construct();
    }
}
