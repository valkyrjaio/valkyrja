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

namespace Valkyrja\Routing\Attributes;

use Attribute;
use Valkyrja\Routing\Models\Route as Model;

/**
 * Attribute Route.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Route extends Model
{
    public function __construct(
        string $path,
        string $name = null,
        array $methods = null,
        array $parameters = null,
        array $middleware = null,
        bool $secure = null,
        string $to = null,
        int $code = null,
    ) {
        $this->path = $path;

        if ($path) {
            $this->name = $path;
        }

        if ($name) {
            $this->name = $name;
        }

        if ($methods) {
            $this->methods = $methods;
        }

        if ($parameters) {
            $this->setParameters($parameters);
        }

        if ($middleware) {
            $this->setMiddleware($middleware);
        }

        if ($secure) {
            $this->secure = $secure;
        }

        if ($to) {
            $this->setTo($to);
        }

        if ($code) {
            $this->code = $code;
        }
    }
}
