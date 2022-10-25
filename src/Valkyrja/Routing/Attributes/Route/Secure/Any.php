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

namespace Valkyrja\Routing\Attributes\Route\Secure;

use Attribute;
use Valkyrja\Routing\Attributes\Route\Any as Model;

/**
 * Attribute Any.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Any extends Model
{
    public function __construct(
        string $path,
        string $name = null,
        array $parameters = null,
        array $middleware = null,
    ) {
        parent::__construct(
            path      : $path,
            name      : $name,
            parameters: $parameters,
            middleware: $middleware,
            secure    : true,
        );
    }
}
