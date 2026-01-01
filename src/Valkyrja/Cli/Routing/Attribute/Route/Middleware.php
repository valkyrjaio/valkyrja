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

namespace Valkyrja\Cli\Routing\Attribute\Route;

use Attribute;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;

/**
 * Attribute Middleware.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Middleware
{
    /**
     * @param class-string<CommandDispatchedMiddlewareContract|CommandMatchedMiddlewareContract|ThrowableCaughtMiddlewareContract|ExitedMiddlewareContract> $name
     */
    public function __construct(
        public string $name
    ) {
    }
}
