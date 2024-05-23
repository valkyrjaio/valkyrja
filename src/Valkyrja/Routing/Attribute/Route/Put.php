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

namespace Valkyrja\Routing\Attribute\Route;

use Attribute;
use Valkyrja\Http\Message\Constant\RequestMethod;
use Valkyrja\Routing\Attribute\Route;
use Valkyrja\Routing\Exception\InvalidRoutePath;
use Valkyrja\Routing\Message\Contract\Message;
use Valkyrja\Routing\Model\Parameter;

/**
 * Attribute Put.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Put extends Route
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
        array|null $parameters = null,
        array|null $middleware = null,
        array|null $messages = null,
        bool|null $secure = null,
        string|null $to = null,
        int|null $code = null,
    ) {
        parent::__construct(
            path: $path,
            name: $name,
            methods: [RequestMethod::PUT],
            parameters: $parameters,
            middleware: $middleware,
            messages: $messages,
            secure: $secure,
            to: $to,
            code: $code,
        );
    }
}
