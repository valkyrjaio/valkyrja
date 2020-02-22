<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing;

/**
 * Interface MethodHelpers.
 *
 * @author Melech Mizrachi
 */
interface MethodHelpers
{
    /**
     * Helper function to set a GET addRoute.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function get(Route $route): void;

    /**
     * Helper function to set a POST addRoute.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function post(Route $route): void;

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function put(Route $route): void;

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function patch(Route $route): void;

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function delete(Route $route): void;

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function head(Route $route): void;

    /**
     * Helper function to set any request method addRoute.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function any(Route $route): void;
}
