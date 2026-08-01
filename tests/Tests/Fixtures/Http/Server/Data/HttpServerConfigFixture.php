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
