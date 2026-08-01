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
