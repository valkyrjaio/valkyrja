<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Routing;

use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Http\RequestMethod;

/**
 * Interface Router
 *
 * @package Valkyrja\Contracts\Http
 *
 * @author  Melech Mizrachi
 */
interface Router
{
    /**
     * Router constructor.
     *
     * @param \Valkyrja\Contracts\Application $application
     */
    public function __construct(Application $application);

    /**
     * Set a single route.
     *
     * @param string $method    The method type (GET, POST, PUT, PATCH, DELETE, HEAD)
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function addRoute(string $method, string $path, array $options, bool $isDynamic = false): void;

    /**
     * Helper function to set a GET addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function get(string $path, array $options, bool $isDynamic = false): void;

    /**
     * Helper function to set a POST addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function post(string $path, array $options, bool $isDynamic = false): void;

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function put(string $path, array $options, bool $isDynamic = false): void;

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function patch(string $path, array $options, bool $isDynamic = false): void;

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function delete(string $path, array $options, bool $isDynamic = false): void;

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function head(string $path, array $options, bool $isDynamic = false): void;

    /**
     * Set routes from a given array of routes.
     *
     * @param array $routes The routes to set
     *
     * @return void
     */
    public function setRoutes(array $routes): void;

    /**
     * Get all routes set by the application.
     *
     * @return array
     */
    public function getRoutes(): array;

    /**
     * Get a route by name.
     *
     * @param string $name   The name of the route to get
     * @param string $method [optional] The method type of get
     * @param string $type   [optional] The type of routes (static/dynamic)
     *
     * @return array
     */
    public function getRouteByName(string $name, string $method = RequestMethod::GET, string $type = 'static'): array;

    /**
     * Get a route url by name.
     *
     * @param string $name   The name of the route to get
     * @param string $method [optional] The method type of get
     * @param array  $data   [optional] The route data if dynamic
     *
     * @return string
     */
    public function getRouteUrlByName(string $name, array $data = [], string $method = RequestMethod::GET): string;

    /**
     * Setup routes.
     *
     * @return void
     */
    public function setupRoutes(): void;

    /**
     * Dispatch the route and find a match.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function dispatch(): Response;
}
