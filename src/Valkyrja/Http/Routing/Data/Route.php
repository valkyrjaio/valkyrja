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

use Valkyrja\Dispatcher\Data\ClassDispatch;
use Valkyrja\Dispatcher\Data\Contract\Dispatch;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Http\Routing\Data\Contract\Parameter;
use Valkyrja\Http\Routing\Data\Contract\Route as Contract;
use Valkyrja\Http\Struct\Request\Contract\RequestStruct;
use Valkyrja\Http\Struct\Response\Contract\ResponseStruct;

use function in_array;

/**
 * Class Route.
 *
 * @author Melech Mizrachi
 */
class Route implements Contract
{
    /**
     * @param RequestMethod[]                           $requestMethods            The request methods
     * @param Parameter[]                               $parameters                The parameters
     * @param class-string<RouteMatchedMiddleware>[]    $routeMatchedMiddleware    The route matched middleware
     * @param class-string<RouteDispatchedMiddleware>[] $routeDispatchedMiddleware The route dispatched middleware
     * @param class-string<ThrowableCaughtMiddleware>[] $throwableCaughtMiddleware The throwable caught middleware
     * @param class-string<SendingResponseMiddleware>[] $sendingResponseMiddleware The sending response middleware
     * @param class-string<TerminatedMiddleware>[]      $terminatedMiddleware      The terminated middleware
     * @param class-string<RequestStruct>|null          $requestStruct             The request struct
     * @param class-string<ResponseStruct>|null         $responseStruct            The response struct
     * @param array<array-key, mixed>|null              $matches                   The dynamic route matches
     */
    public function __construct(
        protected string $path,
        protected string $name,
        protected Dispatch $dispatch = new ClassDispatch(self::class),
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
        protected array|null $matches = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function withPath(string $path): static
    {
        $new = clone $this;

        $new->path = $path;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedPath(string $path): static
    {
        $new = clone $this;

        $new->path = $this->getFilteredPath($this->path) . $this->getFilteredPath($path);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function withName(string $name): static
    {
        $new = clone $this;

        $new->name = $name;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedName(string $name): static
    {
        $new = clone $this;

        $new->name = $this->name . $name;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getDispatch(): Dispatch
    {
        return $this->dispatch;
    }

    /**
     * @inheritDoc
     */
    public function withDispatch(Dispatch $dispatch): static
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
    public function getRequestMethods(): array
    {
        return $this->requestMethods;
    }

    /**
     * @inheritDoc
     */
    public function hasRequestMethod(RequestMethod $requestMethod): bool
    {
        return in_array($requestMethod, $this->requestMethods, true);
    }

    /**
     * @inheritDoc
     */
    public function withRequestMethod(RequestMethod $requestMethod): static
    {
        $new = clone $this;

        $new->requestMethods = [$requestMethod];

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withRequestMethods(RequestMethod ...$requestMethods): static
    {
        $new = clone $this;

        $new->requestMethods = $requestMethods;

        return $new;
    }

    /**
     * @inheritDoc
     */
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
    public function getRegex(): string|null
    {
        return $this->regex;
    }

    /**
     * @inheritDoc
     */
    public function withRegex(string|null $regex = null): static
    {
        $new = clone $this;

        $new->regex = $regex;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getMatches(): array|null
    {
        return $this->matches;
    }

    /**
     * @inheritDoc
     */
    public function withMatches(array|null $matches = null): static
    {
        $new = clone $this;

        $new->matches = $matches;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return array<array-key, Parameter>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @inheritDoc
     */
    public function withParameter(Parameter $parameter): static
    {
        $new = clone $this;

        $new->parameters = [$parameter];

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withParameters(Parameter ...$parameters): static
    {
        $new = clone $this;

        $new->parameters = $parameters;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedParameter(Parameter $parameter): static
    {
        $new = clone $this;

        $new->parameters[] = $parameter;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedParameters(Parameter ...$parameters): static
    {
        $new = clone $this;

        $new->parameters = array_merge($this->parameters, $parameters);

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<RouteMatchedMiddleware>[]
     */
    public function getRouteMatchedMiddleware(): array
    {
        return $this->routeMatchedMiddleware;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<RouteMatchedMiddleware> ...$middleware The middleware
     */
    public function withRouteMatchedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->routeMatchedMiddleware = $middleware;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<RouteMatchedMiddleware> ...$middleware The middleware
     */
    public function withAddedRouteMatchedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->routeMatchedMiddleware = array_merge($this->routeMatchedMiddleware, $middleware);

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<RouteDispatchedMiddleware>[]
     */
    public function getRouteDispatchedMiddleware(): array
    {
        return $this->routeDispatchedMiddleware;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<RouteDispatchedMiddleware> ...$middleware The middleware
     */
    public function withRouteDispatchedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->routeDispatchedMiddleware = $middleware;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<RouteDispatchedMiddleware> ...$middleware The middleware
     */
    public function withAddedRouteDispatchedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->routeDispatchedMiddleware = array_merge($this->routeDispatchedMiddleware, $middleware);

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<ThrowableCaughtMiddleware>[]
     */
    public function getThrowableCaughtMiddleware(): array
    {
        return $this->throwableCaughtMiddleware;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<ThrowableCaughtMiddleware> ...$middleware The middleware
     */
    public function withThrowableCaughtMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->throwableCaughtMiddleware = $middleware;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<ThrowableCaughtMiddleware> ...$middleware The middleware
     */
    public function withAddedThrowableCaughtMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->throwableCaughtMiddleware = array_merge($this->throwableCaughtMiddleware, $middleware);

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<SendingResponseMiddleware>[]
     */
    public function getSendingResponseMiddleware(): array
    {
        return $this->sendingResponseMiddleware;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<SendingResponseMiddleware> ...$middleware The middleware
     */
    public function withSendingResponseMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->sendingResponseMiddleware = $middleware;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<SendingResponseMiddleware> ...$middleware The middleware
     */
    public function withAddedSendingResponseMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->sendingResponseMiddleware = array_merge($this->sendingResponseMiddleware, $middleware);

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<TerminatedMiddleware>[]
     */
    public function getTerminatedMiddleware(): array
    {
        return $this->terminatedMiddleware;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<TerminatedMiddleware> ...$middleware The middleware
     */
    public function withTerminatedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->terminatedMiddleware = $middleware;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<TerminatedMiddleware> ...$middleware The middleware
     */
    public function withAddedTerminatedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->terminatedMiddleware = array_merge($this->terminatedMiddleware, $middleware);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getRequestStruct(): string|null
    {
        return $this->requestStruct;
    }

    /**
     * @inheritDoc
     */
    public function withRequestStruct(string|null $requestStruct = null): static
    {
        $new = clone $this;

        $new->requestStruct = $requestStruct;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getResponseStruct(): string|null
    {
        return $this->responseStruct;
    }

    /**
     * @inheritDoc
     */
    public function withResponseStruct(string|null $responseStruct = null): static
    {
        $new = clone $this;

        $this->responseStruct = $responseStruct;

        return $new;
    }

    /**
     * Validate a path.
     *
     * @param string $path The path
     *
     * @return string
     */
    protected function getFilteredPath(string $path): string
    {
        // Trim slashes from the beginning and end of the path
        if (! $path = trim($path, '/')) {
            // If the path only had a slash return as just slash
            return '/';
        }

        // If the route doesn't begin with an optional or required group
        if ($path[0] !== '[' && $path[0] !== '<') {
            // Append a slash
            return '/' . $path;
        }

        return $path;
    }
}
