<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Annotations;

use Valkyrja\Annotations\Annotation;
use Valkyrja\Http\RequestMethod;

/**
 * Class Route.
 *
 * @author Melech Mizrachi
 */
class Route extends Annotation
{
    /**
     * The path for this route.
     *
     * @var string
     */
    protected $path;

    /**
     * The request methods for this route.
     *
     * @var array
     */
    protected $requestMethods;

    /**
     * The regex for dynamic routes.
     *
     * @var string
     */
    protected $regex;

    /**
     * Any params for dynamic routes.
     *
     * @var array
     */
    protected $params;

    /**
     * Any segments for optional parts of path.
     *
     * @var array
     */
    protected $segments;

    /**
     * The middleware for this route.
     *
     * @var array
     */
    protected $middleware;

    /**
     * Whether the route is dynamic.
     *
     * @var bool
     */
    protected $dynamic = false;

    /**
     * Whether the route is secure.
     *
     * @var bool
     */
    protected $secure = false;

    /**
     * Get the route's path.
     *
     * @return string
     */
    public function getPath():? string
    {
        return $this->path;
    }

    /**
     * Set the route's path.
     *
     * @param string $path The route path
     *
     * @return void
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * Get the request methods.
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function getRequestMethods(): array
    {
        if (null === $this->requestMethods) {
            $this->requestMethods = [
                RequestMethod::GET,
                RequestMethod::HEAD,
            ];
        }

        return $this->requestMethods;
    }

    /**
     * Set the request methods.
     *
     * @param array $requestMethods The request methods
     *
     * @return void
     */
    public function setRequestMethods(array $requestMethods): void
    {
        if (array_diff($requestMethods, RequestMethod::validValues())) {
            throw new \InvalidArgumentException('Invalid request methods set');
        }

        $this->requestMethods = $requestMethods;
    }

    /**
     * Get the regex.
     *
     * @return string
     */
    public function getRegex():? string
    {
        return $this->regex;
    }

    /**
     * Set the regex.
     *
     * @param string $regex The regex
     *
     * @return void
     */
    public function setRegex(string $regex = null): void
    {
        $this->regex = $regex;
    }

    /**
     * Get the params.
     *
     * @return array
     */
    public function getParams():? array
    {
        return $this->params;
    }

    /**
     * Set the params.
     *
     * @param array $params The params
     *
     * @return void
     */
    public function setParams(array $params = null): void
    {
        $this->params = $params;
    }

    /**
     * Get the segments.
     *
     * @return array
     */
    public function getSegments():? array
    {
        return $this->segments;
    }

    /**
     * Set the segments.
     *
     * @param array $segments The segments
     *
     * @return void
     */
    public function setSegments(array $segments = null): void
    {
        $this->segments = $segments;
    }

    /**
     * Get the middleware.
     *
     * @return array
     */
    public function getMiddleware():? array
    {
        return $this->middleware;
    }

    /**
     * Set the middleware.
     *
     * @param array $middleware The middleware
     *
     * @return void
     */
    public function setMiddleware(array $middleware = null): void
    {
        $this->middleware = $middleware;
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
     * @return void
     */
    public function setDynamic(bool $dynamic): void
    {
        $this->dynamic = $dynamic;
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
     * @return void
     */
    public function setSecure(bool $secure): void
    {
        $this->secure = $secure;
    }
}
