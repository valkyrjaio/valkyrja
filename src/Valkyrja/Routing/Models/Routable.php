<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Models;

use InvalidArgumentException;
use Valkyrja\Http\Enums\RequestMethod;
use Valkyrja\Http\Enums\StatusCode;

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
    protected ?string $path = null;

    /**
     * The redirect path for this route.
     *
     * @var string|null
     */
    protected ?string $redirectPath = null;

    /**
     * The redirect status code for this route.
     *
     * @var int
     */
    protected int $redirectCode = StatusCode::FOUND;

    /**
     * The request methods for this route.
     *
     * @var array
     */
    protected array $requestMethods = [
        RequestMethod::GET,
        RequestMethod::HEAD,
    ];

    /**
     * The regex for dynamic routes.
     *
     * @var string|null
     */
    protected ?string $regex = null;

    /**
     * Any params for dynamic routes.
     *
     * @var array|null
     */
    protected ?array $params = null;

    /**
     * Any segments for optional parts of path.
     *
     * @var array|null
     */
    protected ?array $segments = null;

    /**
     * The middleware for this route.
     *
     * @var array|null
     */
    protected ?array $middleware = null;

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
     * Get the route's path.
     *
     * @return string
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
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the redirect path.
     *
     * @return string
     */
    public function getRedirectPath(): ?string
    {
        return $this->redirectPath;
    }

    /**
     * Set the redirect path.
     *
     * @param string|null $redirectPath
     *
     * @return $this
     */
    public function setRedirectPath(string $redirectPath = null): self
    {
        $this->redirectPath = $redirectPath;

        return $this;
    }

    /**
     * Get the redirect status code.
     *
     * @return int
     */
    public function getRedirectCode(): int
    {
        return $this->redirectCode;
    }

    /**
     * Set the redirect status code.
     *
     * @param int $redirectCode
     *
     * @return $this
     */
    public function setRedirectCode(int $redirectCode): self
    {
        $this->redirectCode = $redirectCode;

        return $this;
    }

    /**
     * Get the request methods.
     *
     * @return array
     */
    public function getRequestMethods(): array
    {
        return $this->requestMethods;
    }

    /**
     * Set the request methods.
     *
     * @param array $requestMethods The request methods
     *
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    public function setRequestMethods(array $requestMethods): self
    {
        if (array_diff($requestMethods, RequestMethod::validValues())) {
            throw new InvalidArgumentException('Invalid request methods set');
        }

        $this->requestMethods = $requestMethods;

        return $this;
    }

    /**
     * Get the regex.
     *
     * @return string
     */
    public function getRegex(): ?string
    {
        return $this->regex;
    }

    /**
     * Set the regex.
     *
     * @param string $regex The regex
     *
     * @return $this
     */
    public function setRegex(string $regex = null): self
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * Get the params.
     *
     * @return array
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * Set the params.
     *
     * @param array $params The params
     *
     * @return $this
     */
    public function setParams(array $params = null): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get the segments.
     *
     * @return array
     */
    public function getSegments(): ?array
    {
        return $this->segments;
    }

    /**
     * Set the segments.
     *
     * @param array $segments The segments
     *
     * @return $this
     */
    public function setSegments(array $segments = null): self
    {
        $this->segments = $segments;

        return $this;
    }

    /**
     * Get the middleware.
     *
     * @return array
     */
    public function getMiddleware(): ?array
    {
        return $this->middleware;
    }

    /**
     * Set the middleware.
     *
     * @param array $middleware The middleware
     *
     * @return $this
     */
    public function setMiddleware(array $middleware = null): self
    {
        $this->middleware = $middleware;

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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setRedirect(bool $redirect): self
    {
        $this->redirect = $redirect;

        return $this;
    }
}
