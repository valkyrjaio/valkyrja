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

use JsonSerializable;
use Stringable;
use Valkyrja\Dispatcher\Data\Contract\Dispatch;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Http\Routing\Model\Parameter\Parameter;
use Valkyrja\Http\Struct\Request\Contract\RequestStruct;
use Valkyrja\Http\Struct\Response\Contract\ResponseStruct;

/**
 * Interface Route.
 *
 * @author Melech Mizrachi
 */
interface Route extends JsonSerializable, Stringable
{
    /**
     * Create a new route from an array of data.
     *
     * @param array<string, mixed> $data The data
     *
     * @return static
     */
    public static function fromArray(array $data): static;

    /**
     * Get the dispatch.
     *
     * @return Dispatch
     */
    public function getDispatch(): Dispatch;

    /**
     * Create a new route with the specified dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return static
     */
    public function withDispatch(Dispatch $dispatch): static;

    /**
     * Get the path.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Create a new route with the specified path.
     *
     * @param string $path The path
     *
     * @return static
     */
    public function withPath(string $path): static;

    /**
     * Create a new route by appending a path to the existing path.
     *
     * @param string $path The path to append
     *
     * @return static
     */
    public function withAddedPath(string $path): static;

    /**
     * Get the name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Create a new route with the specified name.
     *
     * @param string $name The name
     *
     * @return static
     */
    public function withName(string $name): static;

    /**
     * Create a new route by appending a name to the existing name.
     *
     * @param string $name The name to append
     *
     * @return static
     */
    public function withAddedName(string $name): static;

    /**
     * Get the request methods.
     *
     * @return RequestMethod[]
     */
    public function getMethods(): array;

    /**
     * Create a new route with the specified request methods.
     *
     * @param RequestMethod ...$methods The request methods
     *
     * @return static
     */
    public function withMethods(RequestMethod ...$methods): static;

    /**
     * Set the request methods.
     *
     * @param RequestMethod ...$methods The request methods
     *
     * @return static
     */
    public function withAddedMethods(RequestMethod ...$methods): static;

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
    public function withRegex(string|null $regex = null): static;

    /**
     * Get the parameters.
     *
     * @return array<int, Parameter>
     */
    public function getParameters(): array;

    /**
     * Create a new route with given parameters.
     *
     * @param Parameter ...$parameter The parameter
     *
     * @return static
     */
    public function withParameters(Parameter ...$parameter): static;

    /**
     * Create a new route with a added parameters.
     *
     * @param Parameter ...$parameter The parameter
     *
     * @return static
     */
    public function withAddedParameters(Parameter ...$parameter): static;

    /**
     * Get all the middleware.
     *
     * @return array<int, class-string<RouteMatchedMiddleware|RouteDispatchedMiddleware|ThrowableCaughtMiddleware|SendingResponseMiddleware|TerminatedMiddleware>>|null
     */
    public function getMiddleware(): array|null;

    /**
     * Get the matched middleware.
     *
     * @return class-string<RouteMatchedMiddleware>[]|null
     */
    public function getMatchedMiddleware(): array|null;

    /**
     * Create a new route with matched middleware.
     *
     * @param class-string<RouteMatchedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withMatchedMiddleware(string ...$middleware): static;

    /**
     * Create a new route with added matched middleware.
     *
     * @param class-string<RouteMatchedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedMatchedMiddleware(string ...$middleware): static;

    /**
     * Get the dispatched middleware.
     *
     * @return class-string<RouteDispatchedMiddleware>[]|null
     */
    public function getDispatchedMiddleware(): array|null;

    /**
     * Create a new route with dispatched middleware.
     *
     * @param class-string<RouteDispatchedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withDispatchedMiddleware(string ...$middleware): static;

    /**
     * Create a new route with added dispatched middleware.
     *
     * @param class-string<RouteDispatchedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedDispatchedMiddleware(string ...$middleware): static;

    /**
     * Get the exception middleware.
     *
     * @return class-string<ThrowableCaughtMiddleware>[]|null
     */
    public function getExceptionMiddleware(): array|null;

    /**
     * Create a new route with exception middleware.
     *
     * @param class-string<ThrowableCaughtMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withExceptionMiddleware(string ...$middleware): static;

    /**
     * Create a new route with added exception middleware.
     *
     * @param class-string<ThrowableCaughtMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedExceptionMiddleware(string ...$middleware): static;

    /**
     * Get the sending middleware.
     *
     * @return class-string<SendingResponseMiddleware>[]|null
     */
    public function getSendingMiddleware(): array|null;

    /**
     * Create a new route with sending middleware.
     *
     * @param class-string<SendingResponseMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withSendingMiddleware(string ...$middleware): static;

    /**
     * Create a new route with added sending middleware.
     *
     * @param class-string<SendingResponseMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedSendingMiddleware(string ...$middleware): static;

    /**
     * Get the terminated middleware.
     *
     * @return class-string<TerminatedMiddleware>[]|null
     */
    public function getTerminatedMiddleware(): array|null;

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

    /**
     * Get the Dispatch as a string.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Serialize properties for json_encode.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array;
}
