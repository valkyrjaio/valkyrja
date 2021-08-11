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

namespace Valkyrja\Routing\Models;

use InvalidArgumentException;
use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Support\Type\Str;

use function array_diff;

/**
 * Trait Routable.
 *
 * @author Melech Mizrachi
 */
trait Routable
{
    /**
     * The path for this route.
     *
     * @var string|null
     */
    public ?string $path = null;

    /**
     * The redirect path for this route.
     *
     * @var string|null
     */
    public ?string $to = null;

    /**
     * The redirect status code for this route.
     *
     * @var int
     */
    public int $code = StatusCode::FOUND;

    /**
     * The request methods for this route.
     *
     * @var array
     */
    public array $methods = [
        RequestMethod::GET,
        RequestMethod::HEAD,
    ];

    /**
     * The regex for dynamic routes.
     *
     * @var string|null
     */
    public ?string $regex = null;

    /**
     * Any params for dynamic routes.
     *
     * @var array|null
     */
    public ?array $params = null;

    /**
     * The dynamic parameters
     *
     * @var RouteParameter[]|null
     */
    public ?array $parameters = null;

    /**
     * Any segments for optional parts of path.
     *
     * @var array|null
     */
    public ?array $segments = null;

    /**
     * The middleware for this route.
     *
     * @var array|null
     */
    public ?array $middleware = null;

    /**
     * Whether the route is dynamic.
     *
     * @var bool
     */
    public bool $dynamic = false;

    /**
     * Whether the route is secure.
     *
     * @var bool
     */
    public bool $secure = false;

    /**
     * Whether the route is a redirect.
     *
     * @var bool
     */
    public bool $redirect = false;

    // /**
    //  * @inheritDoc
    //  */
    // protected static function __getPropertyCastings(): array
    // {
    //     return [
    //         'parameters' => [RouteParameter::class],
    //     ];
    // }

    /**
     * Get the route's path.
     *
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * Set the route's path.
     *
     * @param string $path The route path
     *
     * @return static
     */
    public function setPath(string $path): self
    {
        $this->dynamic = Str::contains($path, '{');

        $this->path = $path;

        return $this;
    }

    /**
     * Get the redirect path.
     *
     * @return string|null
     */
    public function getTo(): ?string
    {
        return $this->to;
    }

    /**
     * Set the redirect path.
     *
     * @param string|null $to
     *
     * @return static
     */
    public function setTo(string $to = null): self
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get the redirect status code.
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Set the redirect status code.
     *
     * @param int $code
     *
     * @return static
     */
    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the request methods.
     *
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Set the request methods.
     *
     * @param array $methods The request methods
     *
     * @throws InvalidArgumentException
     *
     * @return static
     */
    public function setMethods(array $methods): self
    {
        if (array_diff($methods, RequestMethod::ANY)) {
            throw new InvalidArgumentException('Invalid request methods set');
        }

        $this->methods = $methods;

        return $this;
    }

    /**
     * Get the regex.
     *
     * @return string|null
     */
    public function getRegex(): ?string
    {
        return $this->regex;
    }

    /**
     * Set the regex.
     *
     * @param string|null $regex The regex
     *
     * @return static
     */
    public function setRegex(string $regex = null): self
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * Get the params.
     *
     * @return array|null
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * Set the params.
     *
     * @param array|null $params The params
     *
     * @return static
     */
    public function setParams(array $params = null): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get the parameters.
     *
     * @return RouteParameter[]|null
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    /**
     * Set the parameters.
     *
     * @param RouteParameter[]|array[]|null $parameters The parameters
     *
     * @return static
     */
    public function setParameters(array $parameters = null): self
    {
        if ($parameters === null) {
            return $this;
        }

        foreach ($parameters as &$parameter) {
            if (! ($parameter instanceof RouteParameter)) {
                $parameter = RouteParameter::fromArray($parameter);
            }
        }

        unset($parameter);

        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Set a parameter.
     *
     * @param RouteParameter $parameter The parameter
     *
     * @return static
     */
    public function setParameter(RouteParameter $parameter): self
    {
        $this->parameters ??= [];

        $this->parameters[$parameter->getName()] = $parameter;

        return $this;
    }

    /**
     * Add a parameter.
     *
     * @param string      $name                The name
     * @param string      $regex               The regex
     * @param string|null $entity              [optional] The entity class name
     * @param string|null $entityColumn        [optional] The entity column to query against
     * @param array|null  $entityRelationships [optional] The entity relationships
     * @param bool        $isOptional          [optional] Whether the parameter is optional
     * @param bool        $shouldCapture       [optional] Whether this parameter should be captured
     *
     * @return static
     */
    public function addParameter(
        string $name,
        string $regex,
        string $entity = null,
        string $entityColumn = null,
        array $entityRelationships = null,
        bool $isOptional = false,
        bool $shouldCapture = true
    ): self {
        $parameter = new RouteParameter();
        $parameter->setName($name);
        $parameter->setRegex($regex);
        $parameter->setIsOptional($isOptional);
        $parameter->setShouldCapture($shouldCapture);

        if ($entity) {
            $parameter->setEntity($entity);
        }

        if ($entityColumn) {
            $parameter->setEntityColumn($entityColumn);
        }

        if ($entityRelationships) {
            $parameter->setEntityRelationships($entityRelationships);
        }

        return $this->setParameter($parameter);
    }

    /**
     * Get the segments.
     *
     * @return array|null
     */
    public function getSegments(): ?array
    {
        return $this->segments;
    }

    /**
     * Set the segments.
     *
     * @param array|null $segments The segments
     *
     * @return static
     */
    public function setSegments(array $segments = null): self
    {
        $this->segments = $segments;

        return $this;
    }

    /**
     * Get the middleware.
     *
     * @return array|null
     */
    public function getMiddleware(): ?array
    {
        return $this->middleware;
    }

    /**
     * Set the middleware.
     *
     * @param array|null $middleware The middleware
     *
     * @return static
     */
    public function setMiddleware(array $middleware = null): self
    {
        $this->middleware = $middleware;

        return $this;
    }

    /**
     * Route with added middleware.
     *
     * @param array $middleware The middleware
     *
     * @return static
     */
    public function withMiddleware(array $middleware): self
    {
        $this->middleware = array_merge($this->middleware ?? [], $middleware);

        return $this;
    }

    /**
     * Check whether the route is dynamic.
     *
     * @return bool
     */
    public function isDynamic(): bool
    {
        return $this->dynamic;
    }

    /**
     * Set the route as dynamic.
     *
     * @param bool $dynamic Whether the route it dynamic
     *
     * @return static
     */
    public function setDynamic(bool $dynamic = true): self
    {
        $this->dynamic = $dynamic;

        return $this;
    }

    /**
     * Get whether the route is secure.
     *
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * Set whether the route is secure.
     *
     * @param bool $secure Whether the route is secure
     *
     * @return static
     */
    public function setSecure(bool $secure = true): self
    {
        $this->secure = $secure;

        return $this;
    }

    /**
     * Get whether the route is a redirect.
     *
     * @return bool
     */
    public function isRedirect(): bool
    {
        return $this->redirect;
    }

    /**
     * Set whether the route is a redirect.
     *
     * @param bool $redirect
     *
     * @return static
     */
    public function setRedirect(bool $redirect): self
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * Set the parameters.
     *
     * @param RouteParameter[] $parameters The parameters
     *
     * @return void
     */
    protected function __setParameters(RouteParameter ...$parameters): void
    {
        $this->parameters = $parameters;
    }
}
