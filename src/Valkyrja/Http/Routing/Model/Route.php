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

namespace Valkyrja\Http\Routing\Model;

use Valkyrja\Dispatcher\Model\Dispatch;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Exception\InvalidRoutePathException;
use Valkyrja\Http\Routing\Middleware\RedirectRouteMiddleware;
use Valkyrja\Http\Routing\Middleware\SecureRouteMiddleware;
use Valkyrja\Http\Routing\Model\Contract\Route as Contract;
use Valkyrja\Http\Routing\Model\Parameter\Parameter;
use Valkyrja\Http\Struct\Request\Contract\RequestStruct;
use Valkyrja\Http\Struct\Response\Contract\ResponseStruct;
use Valkyrja\Type\BuiltIn\Support\Cls;
use Valkyrja\Type\Data\Cast;

use function array_map;
use function array_merge;
use function assert;
use function is_a;
use function is_array;
use function is_int;
use function is_string;

/**
 * Class Route.
 *
 * @author Melech Mizrachi
 */
class Route extends Dispatch implements Contract
{
    protected const DEFAULT_PATH    = '/';
    protected const DEFAULT_NAME    = null;
    protected const DEFAULT_METHODS = [
        RequestMethod::GET,
        RequestMethod::HEAD,
    ];
    protected const DEFAULT_SECURE  = null;
    protected const DEFAULT_TO      = null;
    protected const DEFAULT_CODE    = null;

    /**
     * The path for this route.
     *
     * @var string
     */
    protected string $path = '/';

    /**
     * The redirect path for this route.
     *
     * @var string|null
     */
    protected string|null $to;

    /**
     * The redirect status code for this route.
     *
     * @var StatusCode|null
     */
    protected StatusCode|null $code;

    /**
     * The request methods for this route.
     *
     * @var RequestMethod[]
     */
    protected array $methods = [
        RequestMethod::GET,
        RequestMethod::HEAD,
    ];

    /**
     * The regex for dynamic routes.
     *
     * @var string|null
     */
    protected string|null $regex;

    /**
     * The dynamic parameters.
     *
     * @var array<int, Parameter>
     */
    protected array $parameters;

    /**
     * The middleware for this route.
     *
     * @var array|null
     */
    protected array|null $middleware;

    /**
     * The middleware for this route.
     *
     * @var class-string<RouteMatchedMiddleware>[]|null
     */
    protected array|null $matchedMiddleware;

    /**
     * The middleware for this route.
     *
     * @var class-string<RouteDispatchedMiddleware>[]|null
     */
    protected array|null $dispatchedMiddleware;

    /**
     * The middleware for this route.
     *
     * @var class-string<ThrowableCaughtMiddleware>[]|null
     */
    protected array|null $exceptionMiddleware;

    /**
     * The middleware for this route.
     *
     * @var class-string<SendingResponseMiddleware>[]|null
     */
    protected array|null $sendingMiddleware;

    /**
     * The middleware for this route.
     *
     * @var class-string<TerminatedMiddleware>[]|null
     */
    protected array|null $terminatedMiddleware;

    /**
     * The request struct for this route.
     *
     * @var class-string<RequestStruct>|null
     */
    protected string|null $requestStruct;

    /**
     * The response struct for this route.
     *
     * @var class-string<ResponseStruct>|null
     */
    protected string|null $responseStruct;

    /**
     * Whether the route is dynamic.
     *
     * @var bool
     */
    protected bool $dynamic = false;

    /**
     * Whether the route is secure.
     *
     * @var bool
     */
    protected bool $secure = false;

    /**
     * Whether the route is a redirect.
     *
     * @var bool
     */
    protected bool $redirect = false;

    /**
     * @param non-empty-string|null                          $path                 [optional] The path
     * @param RequestMethod[]|null                           $methods              The request methods
     * @param Parameter[]|null                               $parameters           The parameters
     * @param class-string<RouteMatchedMiddleware>[]|null    $matchedMiddleware    The matched middleware
     * @param class-string<RouteDispatchedMiddleware>[]|null $dispatchedMiddleware The dispatched middleware
     * @param class-string<ThrowableCaughtMiddleware>[]|null $exceptionMiddleware  The exception middleware
     * @param class-string<SendingResponseMiddleware>[]|null $sendingMiddleware    The sending middleware
     * @param class-string<TerminatedMiddleware>[]|null      $terminatedMiddleware The terminated middleware
     * @param class-string<RequestStruct>|null               $requestStruct        The request struct
     * @param class-string<ResponseStruct>|null              $responseStruct       The response struct
     *
     * @throws InvalidRoutePathException
     */
    public function __construct(
        string|null $path = null,
        string|null $name = null,
        array|null $methods = null,
        array|null $parameters = null,
        array|null $matchedMiddleware = null,
        array|null $dispatchedMiddleware = null,
        array|null $exceptionMiddleware = null,
        array|null $sendingMiddleware = null,
        array|null $terminatedMiddleware = null,
        string|null $requestStruct = null,
        string|null $responseStruct = null,
        bool|null $secure = null,
        string|null $to = null,
        StatusCode|null $code = null,
    ) {
        $path    ??= static::DEFAULT_PATH;
        $name    ??= static::DEFAULT_NAME;
        $methods ??= static::DEFAULT_METHODS;
        $secure  ??= static::DEFAULT_SECURE;
        $to      ??= static::DEFAULT_TO;
        $code    ??= static::DEFAULT_CODE;

        $this->setPath($path);

        if ($name !== null && $name !== '') {
            $this->name = $name;
        }

        if ($methods !== null) {
            $this->setMethods($methods);
        }

        if ($parameters !== null) {
            $this->setParameters($parameters);
        }

        if ($secure !== null) {
            $this->secure = $secure;
        }

        if ($to !== '') {
            $this->setTo($to);
        }

        $this->setMatchedMiddleware($matchedMiddleware);
        $this->setDispatchedMiddleware($dispatchedMiddleware);
        $this->setExceptionMiddleware($exceptionMiddleware);
        $this->setSendingMiddleware($sendingMiddleware);
        $this->setTerminatedMiddleware($terminatedMiddleware);
        $this->setCode($code);
        $this->setRequestStruct($requestStruct);
        $this->setResponseStruct($responseStruct);
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
    public function setPath(string $path): static
    {
        if ($path === '') {
            throw new InvalidRoutePathException('Path must be a non-empty-string.');
        }

        $this->path = $path;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withPath(string $path): static
    {
        $route = clone $this;

        $route->path .= $path;

        return $route;
    }

    /**
     * @inheritDoc
     */
    public function withName(string $name): static
    {
        $route = clone $this;

        $currentName = $this->name ?? '';

        if ($name) {
            $route->name = $currentName
                ? "$currentName.$name"
                : $name;
        }

        return $route;
    }

    /**
     * @inheritDoc
     */
    public function getTo(): string|null
    {
        return $this->to ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setTo(string|null $to = null): static
    {
        if ($to !== null) {
            $this->setRedirect(true);
        }

        $this->to = $to;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getCode(): StatusCode|null
    {
        return $this->code ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setCode(StatusCode|int|null $code = null): static
    {
        $this->code = is_int($code)
            ? StatusCode::from($code)
            : $code;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @inheritDoc
     */
    public function setMethods(array $methods): static
    {
        $this->methods = array_map(
            callback: static fn (RequestMethod|string $method): RequestMethod => is_string($method)
                ? RequestMethod::from($method)
                : $method,
            array: $methods
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRegex(): string|null
    {
        return $this->regex ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setRegex(string|null $regex = null): static
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getParameters(): array
    {
        return $this->parameters ?? [];
    }

    /**
     * @inheritDoc
     */
    public function setParameters(array $parameters): static
    {
        $this->parameters = array_map(
            static fn (Parameter|array $parameter) => is_array($parameter)
                ? Parameter::fromArray($parameter)
                : $parameter,
            $parameters
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setParameter(Parameter $parameter): static
    {
        $this->parameters ??= [];

        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addParameter(
        string $name,
        string|null $regex = null,
        Cast|null $cast = null,
        bool $isOptional = false,
        bool $shouldCapture = true,
        mixed $default = null
    ): static {
        return $this->setParameter(
            new Parameter(
                name: $name,
                regex: $regex ?? Regex::ANY,
                cast: $cast,
                isOptional: $isOptional,
                shouldCapture: $shouldCapture,
                default: $default
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function getMiddleware(): array|null
    {
        return array_merge(
            $this->matchedMiddleware ?? [],
            $this->dispatchedMiddleware ?? [],
            $this->exceptionMiddleware ?? [],
            $this->sendingMiddleware ?? [],
            $this->terminatedMiddleware ?? [],
        );
    }

    /**
     * @inheritDoc
     */
    public function setMiddleware(array|null $middleware = null): static
    {
        $this->matchedMiddleware    = [];
        $this->dispatchedMiddleware = [];
        $this->exceptionMiddleware  = [];
        $this->sendingMiddleware    = [];
        $this->terminatedMiddleware = [];

        $this->addMiddlewares($middleware ?? []);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withMiddleware(array $middleware): static
    {
        $route = clone $this;

        $route->addMiddlewares($middleware);

        return $route;
    }

    /**
     * @inheritDoc
     */
    public function addMiddleware(string $middleware): static
    {
        if (Cls::inherits($middleware, RouteMatchedMiddleware::class)) {
            /** @var class-string<RouteMatchedMiddleware> $middleware */
            $this->matchedMiddleware   ??= [];
            $this->matchedMiddleware[] = $middleware;
        }

        if (Cls::inherits($middleware, RouteDispatchedMiddleware::class)) {
            /** @var class-string<RouteDispatchedMiddleware> $middleware */
            $this->dispatchedMiddleware   ??= [];
            $this->dispatchedMiddleware[] = $middleware;
        }

        if (Cls::inherits($middleware, ThrowableCaughtMiddleware::class)) {
            /** @var class-string<ThrowableCaughtMiddleware> $middleware */
            $this->exceptionMiddleware   ??= [];
            $this->exceptionMiddleware[] = $middleware;
        }

        if (Cls::inherits($middleware, SendingResponseMiddleware::class)) {
            /** @var class-string<SendingResponseMiddleware> $middleware */
            $this->sendingMiddleware   ??= [];
            $this->sendingMiddleware[] = $middleware;
        }

        if (Cls::inherits($middleware, TerminatedMiddleware::class)) {
            /** @var class-string<TerminatedMiddleware> $middleware */
            $this->terminatedMiddleware   ??= [];
            $this->terminatedMiddleware[] = $middleware;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addMiddlewares(array $middleware): static
    {
        array_map(fn (string $middlewareItem) => $this->addMiddleware($middlewareItem), $middleware);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<RouteMatchedMiddleware>[]|null
     */
    public function getMatchedMiddleware(): array|null
    {
        return $this->matchedMiddleware ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setMatchedMiddleware(?array $middleware = null): static
    {
        $this->matchedMiddleware = $middleware;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withMatchedMiddleware(array $middleware): static
    {
        $route = clone $this;

        $route->matchedMiddleware = array_merge($this->matchedMiddleware ?? [], $middleware);

        return $route;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<RouteDispatchedMiddleware>[]|null
     */
    public function getDispatchedMiddleware(): array|null
    {
        return $this->dispatchedMiddleware ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setDispatchedMiddleware(?array $middleware = null): static
    {
        $this->dispatchedMiddleware = $middleware;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withDispatchedMiddleware(array $middleware): static
    {
        $route = clone $this;

        $route->dispatchedMiddleware = array_merge($this->dispatchedMiddleware ?? [], $middleware);

        return $route;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<ThrowableCaughtMiddleware>[]|null
     */
    public function getExceptionMiddleware(): array|null
    {
        return $this->exceptionMiddleware ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setExceptionMiddleware(?array $middleware = null): static
    {
        $this->exceptionMiddleware = $middleware;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withExceptionMiddleware(array $middleware): static
    {
        $route = clone $this;

        $route->exceptionMiddleware = array_merge($this->exceptionMiddleware ?? [], $middleware);

        return $route;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<SendingResponseMiddleware>[]|null
     */
    public function getSendingMiddleware(): array|null
    {
        return $this->sendingMiddleware ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setSendingMiddleware(?array $middleware = null): static
    {
        $this->sendingMiddleware = $middleware;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withSendingMiddleware(array $middleware): static
    {
        $route = clone $this;

        $route->sendingMiddleware = array_merge($this->sendingMiddleware ?? [], $middleware);

        return $route;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<TerminatedMiddleware>[]|null
     */
    public function getTerminatedMiddleware(): array|null
    {
        return $this->terminatedMiddleware ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setTerminatedMiddleware(?array $middleware = null): static
    {
        $this->terminatedMiddleware = $middleware;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withTerminatedMiddleware(array $middleware): static
    {
        $route = clone $this;

        $route->terminatedMiddleware = array_merge($this->terminatedMiddleware ?? [], $middleware);

        return $route;
    }

    /**
     * @inheritDoc
     */
    public function getRequestStruct(): string|null
    {
        return $this->requestStruct ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setRequestStruct(string|null $requestStruct = null): static
    {
        assert($requestStruct === null || is_a($requestStruct, RequestStruct::class, true));

        $this->requestStruct = $requestStruct;

        return $this;
    }

    /**
     * Get the response struct.
     *
     * @return class-string<ResponseStruct>|null
     */
    public function getResponseStruct(): string|null
    {
        return $this->responseStruct ?? null;
    }

    /**
     * Set the response struct
     *
     * @param class-string<ResponseStruct>|null $responseStruct The response struct
     *
     * @return static
     */
    public function setResponseStruct(string|null $responseStruct = null): static
    {
        assert($responseStruct === null || is_a($responseStruct, ResponseStruct::class, true));

        $this->responseStruct = $responseStruct;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isDynamic(): bool
    {
        return $this->dynamic;
    }

    /**
     * @inheritDoc
     */
    public function setDynamic(bool $dynamic = true): static
    {
        $this->dynamic = $dynamic;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * @inheritDoc
     */
    public function setSecure(bool $secure = true): static
    {
        if ($secure) {
            $this->matchedMiddleware[] = SecureRouteMiddleware::class;
        }

        $this->secure = $secure;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isRedirect(): bool
    {
        return $this->redirect;
    }

    /**
     * @inheritDoc
     */
    public function setRedirect(bool $redirect): static
    {
        if ($redirect) {
            $this->matchedMiddleware[] = RedirectRouteMiddleware::class;
        }

        $this->redirect = $redirect;

        return $this;
    }

    /**
     * Set the parameters.
     *
     * @param array<int, Parameter> $parameters The parameters
     *
     * @return void
     */
    protected function internalSetParameters(Parameter ...$parameters): void
    {
        $this->parameters = $parameters;
    }
}
