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

namespace Valkyrja\Routing;

use Closure;

/**
 * Interface RouteGroup.
 *
 * @author Melech Mizrachi
 */
interface RouteGroup
{
    /**
     * Get a router with a path context to group routes with.
     *
     * @param string $path The path
     *
     * @return static
     */
    public function withPath(string $path): self;

    /**
     * Get a router with a controller context to group routes with.
     *
     * @param string $controller The controller
     *
     * @return static
     */
    public function withController(string $controller): self;

    /**
     * Get a router with a name context to group routes with.
     *
     * @param string $name The name
     *
     * @return static
     */
    public function withName(string $name): self;

    /**
     * Get a router with middleware context to group routes with.
     *
     * @param array $middleware The middleware
     *
     * @return static
     */
    public function withMiddleware(array $middleware): self;

    /**
     * Get a router with a secure context to group routes with.
     *
     * @param bool $secure [optional] Whether to be secure
     *
     * @return static
     */
    public function withSecure(bool $secure = true): self;

    /**
     * Group routes together.
     *
     * @param Closure $group The group
     *
     * @return static
     */
    public function group(Closure $group): self;
}
