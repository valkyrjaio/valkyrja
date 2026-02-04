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
use Valkyrja\Attribute\Contract\ReflectionAwareAttributeContract;
use Valkyrja\Attribute\Trait\ReflectionAwareAttribute;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Routing\Data\Contract\ArgumentParameterContract;
use Valkyrja\Cli\Routing\Data\Contract\OptionParameterContract;
use Valkyrja\Cli\Routing\Data\Route as Model;
use Valkyrja\Dispatch\Data\Contract\MethodDispatchContract;
use Valkyrja\Dispatch\Data\MethodDispatch;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route extends Model implements ReflectionAwareAttributeContract
{
    use ReflectionAwareAttribute;

    /**
     * @param non-empty-string                                  $name                      The name
     * @param non-empty-string                                  $description               The description
     * @param (callable():MessageContract)|null                 $helpText                  The help text
     * @param class-string<RouteMatchedMiddlewareContract>[]    $routeMatchedMiddleware    The command matched middleware
     * @param class-string<RouteDispatchedMiddlewareContract>[] $routeDispatchedMiddleware The command dispatched middleware
     * @param class-string<ThrowableCaughtMiddlewareContract>[] $throwableCaughtMiddleware The throwable caught middleware
     * @param class-string<ExitedMiddlewareContract>[]          $exitedMiddleware          The exited middleware
     * @param ArgumentParameterContract[]                       $arguments                 The arguments
     * @param OptionParameterContract[]                         $options                   The options
     */
    public function __construct(
        protected string $name,
        protected string $description,
        callable|null $helpText,
        protected MethodDispatchContract $dispatch = new MethodDispatch(self::class, '__construct'),
        protected array $routeMatchedMiddleware = [],
        protected array $routeDispatchedMiddleware = [],
        protected array $throwableCaughtMiddleware = [],
        protected array $exitedMiddleware = [],
        protected array $arguments = [],
        protected array $options = [],
    ) {
        parent::__construct(
            name: $name,
            description: $description,
            helpText: $helpText,
            dispatch: $dispatch,
            routeMatchedMiddleware: $routeMatchedMiddleware,
            routeDispatchedMiddleware: $routeDispatchedMiddleware,
            throwableCaughtMiddleware: $throwableCaughtMiddleware,
            exitedMiddleware: $exitedMiddleware,
            arguments: $arguments,
            options: $options,
        );
    }
}
