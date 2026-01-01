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
use Valkyrja\Http\Routing\Data\Contract\ParameterContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract as Contract;
use Valkyrja\Http\Struct\Request\Contract\RequestStructContract;
use Valkyrja\Http\Struct\Response\Contract\ResponseStructContract;

use function in_array;

/**
 * Class Route.
 */
class Route implements Contract
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
        protected MethodDispatchContract $dispatch,
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
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withPath(string $path): static
    {
        $new = clone $this;

        $new->path = $this->getFilteredPath($path);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedPath(string $path): static
    {
        $new = clone $this;

        $filteredExistingPath = $this->getFilteredPath($this->path);
        $filteredPath         = $this->getFilteredPath($path);

        $new->path = $this->getFilteredPath($filteredExistingPath . $filteredPath);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withName(string $name): static
    {
        $new = clone $this;

        $new->name = $name;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedName(string $name): static
    {
        $new = clone $this;

        $new->name = $this->name . $name;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDispatch(): MethodDispatchContract
    {
        return $this->dispatch;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withDispatch(MethodDispatchContract $dispatch): static
    {
        $new = clone $this;

        $new->dispatch = $dispatch;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return RequestMethod[]
     */
    #[Override]
    public function getRequestMethods(): array
    {
        return $this->requestMethods;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasRequestMethod(RequestMethod $requestMethod): bool
    {
        return in_array($requestMethod, $this->requestMethods, true);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withRequestMethod(RequestMethod $requestMethod): static
    {
        $new = clone $this;

        $new->requestMethods = [$requestMethod];

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withRequestMethods(RequestMethod ...$requestMethods): static
    {
        $new = clone $this;

        $new->requestMethods = $requestMethods;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedRequestMethod(RequestMethod $requestMethod): static
    {
        $new = clone $this;

        if (! in_array($requestMethod, $this->requestMethods, true)) {
            $new->requestMethods[] = $requestMethod;
        }

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedRequestMethods(RequestMethod ...$requestMethods): static
    {
        $new = clone $this;

        foreach ($requestMethods as $requestMethod) {
            if (! in_array($requestMethod, $this->requestMethods, true)) {
                $new->requestMethods[] = $requestMethod;
            }
        }

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getRegex(): string|null
    {
        return $this->regex;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withRegex(string|null $regex = null): static
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
    public function withParameter(ParameterContract $parameter): static
    {
        $new = clone $this;

        $new->parameters = [$parameter];

        return $new;
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
    public function withAddedParameter(ParameterContract $parameter): static
    {
        $new = clone $this;

        $new->parameters[] = $parameter;

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

    /**
     * @inheritDoc
     *
     * @return class-string<RouteMatchedMiddlewareContract>[]
     */
    #[Override]
    public function getRouteMatchedMiddleware(): array
    {
        return $this->routeMatchedMiddleware;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<RouteMatchedMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withRouteMatchedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->routeMatchedMiddleware = $middleware;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<RouteMatchedMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withAddedRouteMatchedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->routeMatchedMiddleware = array_merge($this->routeMatchedMiddleware, $middleware);

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<RouteDispatchedMiddlewareContract>[]
     */
    #[Override]
    public function getRouteDispatchedMiddleware(): array
    {
        return $this->routeDispatchedMiddleware;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<RouteDispatchedMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withRouteDispatchedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->routeDispatchedMiddleware = $middleware;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<RouteDispatchedMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withAddedRouteDispatchedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->routeDispatchedMiddleware = array_merge($this->routeDispatchedMiddleware, $middleware);

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<ThrowableCaughtMiddlewareContract>[]
     */
    #[Override]
    public function getThrowableCaughtMiddleware(): array
    {
        return $this->throwableCaughtMiddleware;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<ThrowableCaughtMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withThrowableCaughtMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->throwableCaughtMiddleware = $middleware;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<ThrowableCaughtMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withAddedThrowableCaughtMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->throwableCaughtMiddleware = array_merge($this->throwableCaughtMiddleware, $middleware);

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<SendingResponseMiddlewareContract>[]
     */
    #[Override]
    public function getSendingResponseMiddleware(): array
    {
        return $this->sendingResponseMiddleware;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<SendingResponseMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withSendingResponseMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->sendingResponseMiddleware = $middleware;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<SendingResponseMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withAddedSendingResponseMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->sendingResponseMiddleware = array_merge($this->sendingResponseMiddleware, $middleware);

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<TerminatedMiddlewareContract>[]
     */
    #[Override]
    public function getTerminatedMiddleware(): array
    {
        return $this->terminatedMiddleware;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<TerminatedMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withTerminatedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->terminatedMiddleware = $middleware;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<TerminatedMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withAddedTerminatedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->terminatedMiddleware = array_merge($this->terminatedMiddleware, $middleware);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getRequestStruct(): string|null
    {
        return $this->requestStruct;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withRequestStruct(string|null $requestStruct = null): static
    {
        $new = clone $this;

        $new->requestStruct = $requestStruct;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getResponseStruct(): string|null
    {
        return $this->responseStruct;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withResponseStruct(string|null $responseStruct = null): static
    {
        $new = clone $this;

        $new->responseStruct = $responseStruct;

        return $new;
    }

    /**
     * Validate a path.
     *
     * @param non-empty-string $path The path
     *
     * @return non-empty-string
     */
    protected function getFilteredPath(string $path): string
    {
        // Trim slashes from the beginning and end of the path
        if (! $path = trim($path, '/')) {
            // If the path only had a slash return as just slash
            return '/';
        }

        // Prepend a slash
        return '/' . $path;
    }
}
