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

namespace Valkyrja\Http\Routing\Data\Contract;

use Valkyrja\Dispatch\Data\Contract\MethodDispatch;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Http\Struct\Request\Contract\RequestStruct;
use Valkyrja\Http\Struct\Response\Contract\ResponseStruct;

/**
 * Interface Route.
 *
 * @author Melech Mizrachi
 */
interface Route
{
    /**
     * Get the path.
     *
     * @return non-empty-string
     */
    public function getPath(): string;

    /**
     * Create a new route with the specified path.
     *
     * @param non-empty-string $path The path
     *
     * @return static
     */
    public function withPath(string $path): static;

    /**
     * Create a new route by appending a path to the existing path.
     *
     * @param non-empty-string $path The path to append
     *
     * @return static
     */
    public function withAddedPath(string $path): static;

    /**
     * Get the name.
     *
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * Create a new route with the specified name.
     *
     * @param non-empty-string $name The name
     *
     * @return static
     */
    public function withName(string $name): static;

    /**
     * Create a new route by appending a name to the existing name.
     *
     * @param non-empty-string $name The name to append
     *
     * @return static
     */
    public function withAddedName(string $name): static;

    /**
     * Get the dispatch.
     */
    public function getDispatch(): MethodDispatch;

    /**
     * Create a new request method with the specified dispatch.
     *
     * @param MethodDispatch $dispatch The dispatch
     *
     * @return static
     */
    public function withDispatch(MethodDispatch $dispatch): static;

    /**
     * Get the request methods.
     *
     * @return RequestMethod[]
     */
    public function getRequestMethods(): array;

    /**
     * Determine if a request method exists on this route.
     *
     * @param RequestMethod $requestMethod The request method
     *
     * @return bool
     */
    public function hasRequestMethod(RequestMethod $requestMethod): bool;

    /**
     * Create a new route with the specified request method.
     *
     * @param RequestMethod $requestMethod The request method
     *
     * @return static
     */
    public function withRequestMethod(RequestMethod $requestMethod): static;

    /**
     * Create a new route with the specified request methods.
     *
     * @param RequestMethod ...$requestMethods The request methods
     *
     * @return static
     */
    public function withRequestMethods(RequestMethod ...$requestMethods): static;

    /**
     * Create a new route with an additional request method.
     *
     * @param RequestMethod $requestMethod The request method
     *
     * @return static
     */
    public function withAddedRequestMethod(RequestMethod $requestMethod): static;

    /**
     * Create a new route with additional request methods.
     *
     * @param RequestMethod ...$requestMethods The request methods
     *
     * @return static
     */
    public function withAddedRequestMethods(RequestMethod ...$requestMethods): static;

    /**
     * Get the regex.
     *
     * @return non-empty-string|null
     */
    public function getRegex(): string|null;

    /**
     * Set the regex.
     *
     * @param non-empty-string|null $regex The regex
     *
     * @return static
     */
    public function withRegex(string|null $regex = null): static;

    /**
     * Get the parameters.
     *
     * @return array<array-key, Parameter>
     */
    public function getParameters(): array;

    /**
     * Create a new route with given parameter.
     *
     * @param Parameter $parameter The parameter
     *
     * @return static
     */
    public function withParameter(Parameter $parameter): static;

    /**
     * Create a new route with given parameters.
     *
     * @param Parameter ...$parameters The parameter
     *
     * @return static
     */
    public function withParameters(Parameter ...$parameters): static;

    /**
     * Create a new route with added parameter.
     *
     * @param Parameter $parameter The parameter
     *
     * @return static
     */
    public function withAddedParameter(Parameter $parameter): static;

    /**
     * Create a new route with added parameters.
     *
     * @param Parameter ...$parameters The parameter
     *
     * @return static
     */
    public function withAddedParameters(Parameter ...$parameters): static;

    /**
     * Get the matched middleware.
     *
     * @return class-string<RouteMatchedMiddleware>[]
     */
    public function getRouteMatchedMiddleware(): array;

    /**
     * Create a new route with matched middleware.
     *
     * @param class-string<RouteMatchedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withRouteMatchedMiddleware(string ...$middleware): static;

    /**
     * Create a new route with added matched middleware.
     *
     * @param class-string<RouteMatchedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedRouteMatchedMiddleware(string ...$middleware): static;

    /**
     * Get the dispatched middleware.
     *
     * @return class-string<RouteDispatchedMiddleware>[]
     */
    public function getRouteDispatchedMiddleware(): array;

    /**
     * Create a new route with dispatched middleware.
     *
     * @param class-string<RouteDispatchedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withRouteDispatchedMiddleware(string ...$middleware): static;

    /**
     * Create a new route with added dispatched middleware.
     *
     * @param class-string<RouteDispatchedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedRouteDispatchedMiddleware(string ...$middleware): static;

    /**
     * Get the exception middleware.
     *
     * @return class-string<ThrowableCaughtMiddleware>[]
     */
    public function getThrowableCaughtMiddleware(): array;

    /**
     * Create a new route with exception middleware.
     *
     * @param class-string<ThrowableCaughtMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withThrowableCaughtMiddleware(string ...$middleware): static;

    /**
     * Create a new route with added exception middleware.
     *
     * @param class-string<ThrowableCaughtMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedThrowableCaughtMiddleware(string ...$middleware): static;

    /**
     * Get the sending middleware.
     *
     * @return class-string<SendingResponseMiddleware>[]
     */
    public function getSendingResponseMiddleware(): array;

    /**
     * Create a new route with sending middleware.
     *
     * @param class-string<SendingResponseMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withSendingResponseMiddleware(string ...$middleware): static;

    /**
     * Create a new route with added sending middleware.
     *
     * @param class-string<SendingResponseMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedSendingResponseMiddleware(string ...$middleware): static;

    /**
     * Get the terminated middleware.
     *
     * @return class-string<TerminatedMiddleware>[]
     */
    public function getTerminatedMiddleware(): array;

    /**
     * Create a new route with terminated middleware.
     *
     * @param class-string<TerminatedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withTerminatedMiddleware(string ...$middleware): static;

    /**
     * Create a new route with added terminated middleware.
     *
     * @param class-string<TerminatedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedTerminatedMiddleware(string ...$middleware): static;

    /**
     * Get the request struct.
     *
     * @return class-string<RequestStruct>|null
     */
    public function getRequestStruct(): string|null;

    /**
     * Create a new route with a specified RequestStruct.
     *
     * @param class-string<RequestStruct>|null $requestStruct The request struct
     *
     * @return static
     */
    public function withRequestStruct(string|null $requestStruct = null): static;

    /**
     * Get the response struct.
     *
     * @return class-string<ResponseStruct>|null
     */
    public function getResponseStruct(): string|null;

    /**
     * Create a new route with a specified ResponseStruct.
     *
     * @param class-string<ResponseStruct>|null $responseStruct The response struct
     *
     * @return static
     */
    public function withResponseStruct(string|null $responseStruct = null): static;
}
