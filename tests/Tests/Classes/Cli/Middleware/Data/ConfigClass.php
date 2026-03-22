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

namespace Valkyrja\Tests\Classes\Cli\Middleware\Data;

use Valkyrja\Cli\Middleware\Data\Contract\ConfigContract;

final class ConfigClass implements ConfigContract
{
    public function __construct(
        public array $inputReceivedMiddleware = [],
        public array $routeMatchedMiddleware = [],
        public array $routeNotMatchedMiddleware = [],
        public array $routeDispatchedMiddleware = [],
        public array $throwableCaughtMiddleware = [],
        public array $exitedMiddleware = [],
    ) {
    }
}
