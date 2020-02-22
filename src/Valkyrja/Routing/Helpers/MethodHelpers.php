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

namespace Valkyrja\Routing\Helpers;

use Exception;
use Valkyrja\Http\Enums\RequestMethod;
use Valkyrja\Routing\Route;

/**
 * Trait MethodHelpers.
 *
 * @author Melech Mizrachi
 */
trait MethodHelpers
{
    /**
     * Helper function to set a GET addRoute.
     *
     * @param Route $route The route
     *
     * @throws Exception
     *
     * @return void
     */
    public function get(Route $route): void
    {
        $route->setMethods([RequestMethod::GET, RequestMethod::HEAD]);

        $this->addRoute($route);
    }

    /**
     * Helper function to set a POST addRoute.
     *
     * @param Route $route The route
     *
     * @throws Exception
     *
     * @return void
     */
    public function post(Route $route): void
    {
        $route->setMethods([RequestMethod::POST]);

        $this->addRoute($route);
    }

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param Route $route The route
     *
     * @throws Exception
     *
     * @return void
     */
    public function put(Route $route): void
    {
        $route->setMethods([RequestMethod::PUT]);

        $this->addRoute($route);
    }

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param Route $route The route
     *
     * @throws Exception
     *
     * @return void
     */
    public function patch(Route $route): void
    {
        $route->setMethods([RequestMethod::PATCH]);

        $this->addRoute($route);
    }

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param Route $route The route
     *
     * @throws Exception
     *
     * @return void
     */
    public function delete(Route $route): void
    {
        $route->setMethods([RequestMethod::DELETE]);

        $this->addRoute($route);
    }

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param Route $route The route
     *
     * @throws Exception
     *
     * @return void
     */
    public function head(Route $route): void
    {
        $route->setMethods([RequestMethod::HEAD]);

        $this->addRoute($route);
    }

    /**
     * Helper function to set any request method addRoute.
     *
     * @param Route $route The route
     *
     * @throws Exception
     *
     * @return void
     */
    public function any(Route $route): void
    {
        $route->setMethods(RequestMethod::ANY);

        $this->addRoute($route);
    }

    /**
     * Set a single route.
     *
     * @param Route $route
     *
     * @return void
     */
    abstract public function addRoute(Route $route): void;
}
