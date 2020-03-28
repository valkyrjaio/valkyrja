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
use Valkyrja\Http\Enums\RequestMethod;
use Valkyrja\Http\Enums\StatusCode;

use function array_diff;
use function strpos;

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
    protected ?string $to = null;

    /**
     * The redirect status code for this route.
     *
     * @var int
     */
    protected int $code = StatusCode::FOUND;

    /**
     * The request methods for this route.
     *
     * @var array
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
     * @return static
     */
    public function setPath(string $path): self
    {
        $this->dynamic = strpos($path, '{') !== false;

        $this->path = $path;

        return $this;
    }

    /**
     * Get the redirect path.
     *
     * @return string
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
        if (array_diff($methods, RequestMethod::getValidValues())) {
            throw new InvalidArgumentException('Invalid request methods set');
        }

        $this->methods = $methods;

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
     * @return static
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
     * @return static
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
}
