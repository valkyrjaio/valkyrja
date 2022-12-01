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
use Valkyrja\Routing\Attributes\Route;

/**
 * Attribute Secure.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Secure extends Route
{
    public function __construct(
        string $path,
        string $name = null,
        array $methods = null,
        array $parameters = null,
        array $middleware = null,
        string $to = null,
        int $code = null,
    ) {
        parent::__construct(
            path      : $path,
            name      : $name,
            methods   : $methods,
            parameters: $parameters,
            middleware: $middleware,
            secure    : true,
            to        : $to,
            code      : $code,
        );
    }
}
