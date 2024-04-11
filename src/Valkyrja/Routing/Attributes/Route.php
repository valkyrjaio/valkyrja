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
use Valkyrja\Routing\Exceptions\InvalidRoutePath;
use Valkyrja\Routing\Message;
use Valkyrja\Routing\Models\Parameter;
use Valkyrja\Routing\Models\Route as Model;

/**
 * Attribute Route.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Route extends Model
{
    /**
     * @param non-empty-string             $path       The path
     * @param Parameter[]|null             $parameters The parameters
     * @param class-string<Message>[]|null $messages   The messages
     *
     * @throws InvalidRoutePath
     */
    public function __construct(
        string $path,
        string|null $name = null,
        array|null $methods = null,
        array|null $parameters = null,
        array|null $middleware = null,
        array|null $messages = null,
        bool|null $secure = null,
        string|null $to = null,
        int|null $code = null,
    ) {
        $this->path = $path;

        /** @psalm-suppress TypeDoesNotContainType Not everyone will use Psalm :) */
        if ($path === '') {
            throw new InvalidRoutePath("Path must be a non-empty-string.");
        }

        if ($name !== null && $name !== '') {
            $this->name = $name;
        } else {
            $this->name = $path;
        }

        if ($methods !== null) {
            $this->methods = $methods;
        }

        if ($parameters !== null) {
            $this->setParameters($parameters);
        }

        if ($secure !== null) {
            $this->secure = $secure;
        }

        if ($to !== '') {
            $this->setTo($to);
        }

        $this->setMiddleware($middleware);
        $this->setCode($code);
        $this->setMessages($messages);
    }
}
