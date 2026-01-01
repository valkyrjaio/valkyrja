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

namespace Valkyrja\Http\Routing\Attribute;

use Attribute;
use Valkyrja\Dispatch\Data\Contract\MethodDispatchContract;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Http\Routing\Data\Contract\ParameterContract;
use Valkyrja\Http\Routing\Data\Route as ParentRoute;
use Valkyrja\Http\Struct\Request\Contract\RequestStructContract;
use Valkyrja\Http\Struct\Response\Contract\ResponseStructContract;

/**
 * Attribute Route.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route extends ParentRoute
{
    /**
     * @param non-empty-string                                  $path                      The path
     * @param non-empty-string                                  $name                      The name
     * @param non-empty-string|null                             $regex                     The regex
     * @param RequestMethod[]                                   $requestMethods            The request methods
     * @param ParameterContract[]                               $parameters                The parameters
     * @param class-string<RouteMatchedMiddlewareContract>[]    $routeMatchedMiddleware    The route matched middleware
     * @param class-string<RouteDispatchedMiddlewareContract>[] $routeDispatchedMiddleware The route dispatched middleware
     * @param class-string<ThrowableCaughtMiddlewareContract>[] $throwableCaughtMiddleware The throwable caught middleware
     * @param class-string<SendingResponseMiddlewareContract>[] $sendingResponseMiddleware The sending response middleware
     * @param class-string<TerminatedMiddlewareContract>[]      $terminatedMiddleware      The terminated middleware
     * @param class-string<RequestStructContract>|null          $requestStruct             The request struct
     * @param class-string<ResponseStructContract>|null         $responseStruct            The response struct
     */
    public function __construct(
        protected string $path,
        protected string $name,
        protected MethodDispatchContract $dispatch = new MethodDispatch(self::class, 'getPath'),
        protected array $requestMethods = [RequestMethod::HEAD, RequestMethod::GET],
        protected string|null $regex = null,
        protected array $parameters = [],
        protected array $routeMatchedMiddleware = [],
        protected array $routeDispatchedMiddleware = [],
        protected array $throwableCaughtMiddleware = [],
        protected array $sendingResponseMiddleware = [],
        protected array $terminatedMiddleware = [],
        protected string|null $requestStruct = null,
        protected string|null $responseStruct = null,
    ) {
        parent::__construct(
            path: $path,
            name: $name,
            dispatch: $dispatch,
            requestMethods: $requestMethods,
            regex: $regex,
            parameters: $parameters,
            routeMatchedMiddleware: $routeMatchedMiddleware,
            routeDispatchedMiddleware: $routeDispatchedMiddleware,
            throwableCaughtMiddleware: $throwableCaughtMiddleware,
            sendingResponseMiddleware: $sendingResponseMiddleware,
            terminatedMiddleware: $terminatedMiddleware,
            requestStruct: $requestStruct,
            responseStruct: $responseStruct,
        );
    }
}
