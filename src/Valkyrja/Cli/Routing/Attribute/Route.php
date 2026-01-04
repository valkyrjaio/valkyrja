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

namespace Valkyrja\Cli\Routing\Attribute;

use Attribute;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Routing\Data\Contract\ParameterContract;
use Valkyrja\Cli\Routing\Data\Route as Model;
use Valkyrja\Dispatch\Data\Contract\MethodDispatchContract;
use Valkyrja\Dispatch\Data\MethodDispatch;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route extends Model
{
    /**
     * @param non-empty-string                                  $name                        The name
     * @param non-empty-string                                  $description                 The description
     * @param MessageContract                                   $helpText                    The help text
     * @param class-string<RouteMatchedMiddlewareContract>[]    $commandMatchedMiddleware    The command matched middleware
     * @param class-string<RouteDispatchedMiddlewareContract>[] $commandDispatchedMiddleware The command dispatched middleware
     * @param class-string<ThrowableCaughtMiddlewareContract>[] $throwableCaughtMiddleware   The throwable caught middleware
     * @param class-string<ExitedMiddlewareContract>[]          $exitedMiddleware            The exited middleware
     * @param ParameterContract[]                               $parameters                  The parameters
     */
    public function __construct(
        protected string $name,
        protected string $description,
        protected MessageContract $helpText,
        protected MethodDispatchContract $dispatch = new MethodDispatch(self::class, '__construct'),
        protected array $commandMatchedMiddleware = [],
        protected array $commandDispatchedMiddleware = [],
        protected array $throwableCaughtMiddleware = [],
        protected array $exitedMiddleware = [],
        array $parameters = [],
    ) {
        parent::__construct(
            name: $name,
            description: $description,
            helpText: $helpText,
            dispatch: $dispatch,
            commandMatchedMiddleware: $commandMatchedMiddleware,
            commandDispatchedMiddleware: $commandDispatchedMiddleware,
            throwableCaughtMiddleware: $throwableCaughtMiddleware,
            exitedMiddleware: $exitedMiddleware,
            parameters: $parameters,
        );
    }
}
