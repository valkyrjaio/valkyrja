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
