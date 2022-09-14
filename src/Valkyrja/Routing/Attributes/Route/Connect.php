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

namespace Valkyrja\Routing\Attributes\Route;

use Attribute;
use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Routing\Attributes\Route;

/**
 * Attribute Connect.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Connect extends Route
{
    public function __construct(
        string $path,
        array $parameters = null,
        array $middleware = null,
        bool $secure = null,
    ) {
        parent::__construct(
            path      : $path,
            methods   : [
                RequestMethod::CONNECT,
            ],
            parameters: $parameters,
            middleware: $middleware,
            secure    : $secure,
        );
    }
}
