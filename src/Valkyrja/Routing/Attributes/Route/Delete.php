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
 * Attribute Delete.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Delete extends Route
{
    public function __construct(
        string $path,
        string $name = null,
        array $parameters = null,
        array $middleware = null,
        bool $secure = null,
        string $to = null,
        int $code = null,
    ) {
        parent::__construct(
            path      : $path,
            name      : $name,
            methods   : [RequestMethod::DELETE],
            parameters: $parameters,
            middleware: $middleware,
            secure    : $secure,
            to        : $to,
            code      : $code,
        );
    }
}
