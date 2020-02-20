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

namespace Valkyrja\Routing;

use Valkyrja\Dispatcher\Dispatch;

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
    public function getPath(): ?string;

    /**
     * Set the route's path.
     *
     * @param string $path The route path
     *
     * @return $this
     */
    public function setPath(string $path): self;

    /**
     * Get the redirect path.
     *
     * @return string
     */
    public function getRedirectPath(): ?string;

    /**
     * Set the redirect path.
     *
     * @param string|null $redirectPath
     *
     * @return $this
     */
    public function setRedirectPath(string $redirectPath = null): self;

    /**
     * Get the redirect status code.
     *
     * @return int
     */
    public function getRedirectCode(): int;

    /**
     * Set the redirect status code.
     *
     * @param int $redirectCode
     *
     * @return $this
     */
    public function setRedirectCode(int $redirectCode): self;

    /**
     * Get the request methods.
     *
     * @return array
     */
    public function getRequestMethods(): array;

    /**
     * Set the request methods.
     *
     * @param array $requestMethods The request methods
     *
     * @return $this
     */
    public function setRequestMethods(array $requestMethods): self;

    /**
     * Get the regex.
     *
     * @return string
     */
    public function getRegex(): ?string;

    /**
     * Set the regex.
     *
     * @param string $regex The regex
     *
     * @return $this
     */
    public function setRegex(string $regex = null): self;

    /**
     * Get the params.
     *
     * @return array
     */
    public function getParams(): ?array;

    /**
     * Set the params.
     *
     * @param array $params The params
     *
     * @return $this
     */
    public function setParams(array $params = null): self;

    /**
     * Get the segments.
     *
     * @return array
     */
    public function getSegments(): ?array;

    /**
     * Set the segments.
     *
     * @param array $segments The segments
     *
     * @return $this
     */
    public function setSegments(array $segments = null): self;

    /**
     * Get the middleware.
     *
     * @return array
     */
    public function getMiddleware(): ?array;

    /**
     * Set the middleware.
     *
     * @param array $middleware The middleware
     *
     * @return $this
     */
    public function setMiddleware(array $middleware = null): self;

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
     * @return $this
     */
    public function setDynamic(bool $dynamic = true): self;

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
     * @return $this
     */
    public function setSecure(bool $secure = true): self;

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
     * @return $this
     */
    public function setRedirect(bool $redirect): self;
}
