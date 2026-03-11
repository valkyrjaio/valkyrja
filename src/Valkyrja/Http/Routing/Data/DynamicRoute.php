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

namespace Valkyrja\Http\Routing\Data;

use Override;
use Valkyrja\Dispatch\Data\Contract\MethodDispatchContract;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Http\Routing\Data\Contract\DynamicRouteContract;
use Valkyrja\Http\Routing\Data\Contract\ParameterContract;
use Valkyrja\Http\Struct\Request\Contract\RequestStructContract;
use Valkyrja\Http\Struct\Response\Contract\ResponseStructContract;

class DynamicRoute extends Route implements DynamicRouteContract
{
    /**
     * @param non-empty-string                                  $path                      The path
     * @param non-empty-string                                  $name                      The name
     * @param RequestMethod[]                                   $requestMethods            The request methods
     * @param ParameterContract[]                               $parameters                The parameters
     * @param class-string<RouteMatchedMiddlewareContract>[]    $routeMatchedMiddleware    The route matched middleware
     * @param class-string<RouteDispatchedMiddlewareContract>[] $routeDispatchedMiddleware The route dispatched middleware
     * @param class-string<ThrowableCaughtMiddlewareContract>[] $throwableCaughtMiddleware The throwable caught middleware
     * @param class-string<SendingResponseMiddlewareContract>[] $sendingResponseMiddleware The sending response middleware
     * @param class-string<TerminatedMiddlewareContract>[]      $terminatedMiddleware      The terminated middleware
     */
    public function __construct(
        protected string $path,
        protected string $name,
        protected string $regex,
        protected array $parameters,
        protected MethodDispatchContract $dispatch,
        protected array $requestMethods = [RequestMethod::HEAD, RequestMethod::GET],
        protected array $routeMatchedMiddleware = [],
        protected array $routeDispatchedMiddleware = [],
        protected array $throwableCaughtMiddleware = [],
        protected array $sendingResponseMiddleware = [],
        protected array $terminatedMiddleware = [],
        protected RequestStructContract|null $requestStruct = null,
        protected ResponseStructContract|null $responseStruct = null,
    ) {
        parent::__construct(
            path: $path,
            name: $name,
            dispatch: $dispatch,
            requestMethods: $requestMethods,
            routeMatchedMiddleware: $routeMatchedMiddleware,
            routeDispatchedMiddleware: $routeDispatchedMiddleware,
            throwableCaughtMiddleware: $throwableCaughtMiddleware,
            sendingResponseMiddleware: $sendingResponseMiddleware,
            terminatedMiddleware: $terminatedMiddleware,
            requestStruct: $requestStruct,
            responseStruct: $responseStruct,
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getRegex(): string
    {
        return $this->regex;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withRegex(string $regex): static
    {
        $new = clone $this;

        $new->regex = $regex;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return array<array-key, ParameterContract>
     */
    #[Override]
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withParameters(ParameterContract ...$parameters): static
    {
        $new = clone $this;

        $new->parameters = $parameters;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedParameters(ParameterContract ...$parameters): static
    {
        $new = clone $this;

        $new->parameters = array_merge($this->parameters, $parameters);

        return $new;
    }
}
