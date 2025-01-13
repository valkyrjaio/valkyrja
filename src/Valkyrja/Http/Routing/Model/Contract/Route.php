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

namespace Valkyrja\Http\Routing\Model\Contract;

use Valkyrja\Dispatcher\Model\Contract\Dispatch;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Http\Routing\Model\Parameter\Parameter;
use Valkyrja\Http\Struct\Request\Contract\RequestStruct;
use Valkyrja\Http\Struct\Response\Contract\ResponseStruct;
use Valkyrja\Type\Data\Cast;

/**
 * Interface Route.
 *
 * @author Melech Mizrachi
 */
interface Route extends Dispatch
{
    /**
     * Get the route's path.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Set the route's path.
     *
     * @param string $path The route path
     *
     * @return static
     */
    public function setPath(string $path): static;

    /**
     * Append a path to the existing path.
     *
     * @param string $path The path to append
     *
     * @return static
     */
    public function withPath(string $path): static;

    /**
     * Append a name to the existing name.
     *
     * @param string $name The name to append
     *
     * @return static
     */
    public function withName(string $name): static;

    /**
     * Get the redirect path.
     *
     * @return string|null
     */
    public function getTo(): string|null;

    /**
     * Set the redirect path.
     *
     * @param string|null $to The path to redirect to
     *
     * @return static
     */
    public function setTo(string|null $to = null): static;

    /**
     * Get the redirect status code.
     *
     * @return StatusCode|null
     */
    public function getCode(): StatusCode|null;

    /**
     * Set the redirect status code.
     *
     * @param StatusCode|int|null $code The status code
     *
     * @return static
     */
    public function setCode(StatusCode|int|null $code = null): static;

    /**
     * Get the request methods.
     *
     * @return RequestMethod[]
     */
    public function getMethods(): array;

    /**
     * Set the request methods.
     *
     * @param RequestMethod[]|string[] $methods The request methods
     *
     * @return static
     */
    public function setMethods(array $methods): static;

    /**
     * Get the regex.
     *
     * @return string|null
     */
    public function getRegex(): string|null;

    /**
     * Set the regex.
     *
     * @param string|null $regex The regex
     *
     * @return static
     */
    public function setRegex(string|null $regex = null): static;

    /**
     * Get the parameters.
     *
     * @return array<int, Parameter>
     */
    public function getParameters(): array;

    /**
     * Set the parameters.
     *
     * @param array<int, Parameter>|array<array-key, array<string, mixed>> $parameters The parameters
     *
     * @return static
     */
    public function setParameters(array $parameters): static;

    /**
     * Set a parameter.
     *
     * @param Parameter $parameter The parameter
     *
     * @return static
     */
    public function setParameter(Parameter $parameter): static;

    /**
     * Add a parameter.
     *
     * @param string      $name          The name
     * @param string|null $regex         [optional] The regex
     * @param Cast|null   $cast          [optional] The cast
     * @param bool        $isOptional    [optional] Whether the parameter is optional
     * @param bool        $shouldCapture [optional] Whether this parameter should be captured
     * @param mixed       $default       [optional] The default value for this parameter
     *
     * @return static
     */
    public function addParameter(
        string $name,
        string|null $regex = null,
        Cast|null $cast = null,
        bool $isOptional = false,
        bool $shouldCapture = true,
        mixed $default = null
    ): static;

    /**
     * Get all the middleware.
     *
     * @return class-string[]|null
     */
    public function getMiddleware(): array|null;

    /**
     * Set the middleware.
     *
     * @param class-string[]|null $middleware The middleware
     *
     * @return static
     */
    public function setMiddleware(array|null $middleware = null): static;

    /**
     * Route with added middleware.
     *
     * @param class-string[] $middleware The middleware
     *
     * @return static
     */
    public function withMiddleware(array $middleware): static;

    /**
     * Add a single middleware.
     *
     * @param class-string $middleware The middleware
     *
     * @return static
     */
    public function addMiddleware(string $middleware): static;

    /**
     * Add an array of middleware.
     *
     * @param class-string[] $middleware The middleware
     *
     * @return static
     */
    public function addMiddlewares(array $middleware): static;

    /**
     * Get the matched middleware.
     *
     * @return class-string<RouteMatchedMiddleware>[]|null
     */
    public function getMatchedMiddleware(): array|null;

    /**
     * Set the matched middleware.
     *
     * @param class-string<RouteMatchedMiddleware>[]|null $middleware The middleware
     *
     * @return static
     */
    public function setMatchedMiddleware(array|null $middleware = null): static;

    /**
     * Route with added matched middleware.
     *
     * @param class-string<RouteMatchedMiddleware>[] $middleware The middleware
     *
     * @return static
     */
    public function withMatchedMiddleware(array $middleware): static;

    /**
     * Get the dispatched middleware.
     *
     * @return class-string<RouteDispatchedMiddleware>[]|null
     */
    public function getDispatchedMiddleware(): array|null;

    /**
     * Set the dispatched middleware.
     *
     * @param class-string<RouteDispatchedMiddleware>[]|null $middleware The middleware
     *
     * @return static
     */
    public function setDispatchedMiddleware(array|null $middleware = null): static;

    /**
     * Route with added dispatched middleware.
     *
     * @param class-string<RouteDispatchedMiddleware>[] $middleware The middleware
     *
     * @return static
     */
    public function withDispatchedMiddleware(array $middleware): static;

    /**
     * Get the exception middleware.
     *
     * @return class-string<ThrowableCaughtMiddleware>[]|null
     */
    public function getExceptionMiddleware(): array|null;

    /**
     * Set the exception middleware.
     *
     * @param class-string<ThrowableCaughtMiddleware>[]|null $middleware The middleware
     *
     * @return static
     */
    public function setExceptionMiddleware(array|null $middleware = null): static;

    /**
     * Route with added exception middleware.
     *
     * @param class-string<ThrowableCaughtMiddleware>[] $middleware The middleware
     *
     * @return static
     */
    public function withExceptionMiddleware(array $middleware): static;

    /**
     * Get the sending middleware.
     *
     * @return class-string<SendingResponseMiddleware>[]|null
     */
    public function getSendingMiddleware(): array|null;

    /**
     * Set the sending middleware.
     *
     * @param class-string<SendingResponseMiddleware>[]|null $middleware The middleware
     *
     * @return static
     */
    public function setSendingMiddleware(array|null $middleware = null): static;

    /**
     * Route with added sending middleware.
     *
     * @param class-string<SendingResponseMiddleware>[] $middleware The middleware
     *
     * @return static
     */
    public function withSendingMiddleware(array $middleware): static;

    /**
     * Get the terminated middleware.
     *
     * @return class-string<TerminatedMiddleware>[]|null
     */
    public function getTerminatedMiddleware(): array|null;

    /**
     * Set the terminated middleware.
     *
     * @param class-string<TerminatedMiddleware>[]|null $middleware The middleware
     *
     * @return static
     */
    public function setTerminatedMiddleware(array|null $middleware = null): static;

    /**
     * Route with added terminated middleware.
     *
     * @param class-string<TerminatedMiddleware>[] $middleware The middleware
     *
     * @return static
     */
    public function withTerminatedMiddleware(array $middleware): static;

    /**
     * Get the request struct.
     *
     * @return class-string<RequestStruct>|null
     */
    public function getRequestStruct(): string|null;

    /**
     * Set the request struct.
     *
     * @param class-string<RequestStruct>|null $requestStruct The request struct
     *
     * @return static
     */
    public function setRequestStruct(string|null $requestStruct = null): static;

    /**
     * Get the response struct.
     *
     * @return class-string<ResponseStruct>|null
     */
    public function getResponseStruct(): string|null;

    /**
     * Set the response struct
     *
     * @param class-string<ResponseStruct>|null $responseStruct The response struct
     *
     * @return static
     */
    public function setResponseStruct(string|null $responseStruct = null): static;

    /**
     * Check whether the route is dynamic.
     *
     * @return bool
     */
    public function isDynamic(): bool;

    /**
     * Set the route as dynamic.
     *
     * @param bool $dynamic Whether the route it dynamic
     *
     * @return static
     */
    public function setDynamic(bool $dynamic = true): static;

    /**
     * Get whether the route is secure.
     *
     * @return bool
     */
    public function isSecure(): bool;

    /**
     * Set whether the route is secure.
     *
     * @param bool $secure Whether the route is secure
     *
     * @return static
     */
    public function setSecure(bool $secure = true): static;

    /**
     * Get whether the route is a redirect.
     *
     * @return bool
     */
    public function isRedirect(): bool;

    /**
     * Set whether the route is a redirect.
     *
     * @param bool $redirect
     *
     * @return static
     */
    public function setRedirect(bool $redirect): static;
}
