<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Http;

use Valkyrja\Contracts\Application;

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
    public function addRoute(string $method, string $path, array $options, bool $isDynamic = false) : void;

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
    public function get(string $path, array $options, bool $isDynamic = false) : void;

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
    public function post(string $path, array $options, bool $isDynamic = false) : void;

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
    public function put(string $path, array $options, bool $isDynamic = false) : void;

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
    public function patch(string $path, array $options, bool $isDynamic = false) : void;

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
    public function delete(string $path, array $options, bool $isDynamic = false) : void;

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
    public function head(string $path, array $options, bool $isDynamic = false) : void;

    /**
     * Set routes from a given array of routes.
     *
     * @param array $routes The routes to set
     *
     * @return void
     */
    public function setRoutes(array $routes) : void;

    /**
     * Get all routes set by the application.
     *
     * @return array
     */
    public function getRoutes() : array;

    /**
     * Dispatch the route and find a match.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function dispatch() : Response;
}
