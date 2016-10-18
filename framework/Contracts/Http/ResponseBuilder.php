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

use Valkyrja\Contracts\View\View;

/**
 * Interface ResponseBuilder
 *
 * @package Valkyrja\Contracts\Http
 *
 * @author  Melech Mizrachi
 */
interface ResponseBuilder
{
    /**
     * ResponseBuilder constructor.
     *
     * @param View     $view     The View class to use
     * @param Response $response The Response class to use
     */
    public function __construct(Response $response, View $view);

    /**
     * Make a new instance of Response.
     *
     * @param mixed $content [optional] The response content
     * @param int   $status  [optional] The response status code
     * @param array $headers [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function make($content = '', $status = 200, array $headers = []);

    /**
     * View response builder.
     *
     * @param string $view    The view template to use
     * @param array  $data    [optional] The view data
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function view($view, array $data = [], $status = 200, array $headers = []);

    /**
     * Json response builder.
     *
     * @param mixed $data    [optional] The data to set
     * @param int   $status  [optional] The response status code
     * @param array $headers [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function json($data = [], $status = 200, array $headers = []);

    /**
     * JsonP response builder.
     *
     * @param string $callback The jsonp callback
     * @param mixed  $data     [optional] The data to set
     * @param int    $status   [optional] The response status code
     * @param array  $headers  [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function jsonp($callback, $data = [], $status = 200, array $headers = []);

    /**
     * Redirect to response builder.
     *
     * @param string $path       The path to redirect to
     * @param array  $parameters [optional] Any parameters to set for dynamic paths
     * @param int    $status     [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function redirectTo($path, array $parameters = [], $status = 302, array $headers = []);

    /**
     * Redirect to a named route response builder.
     *
     * @param string $route      The route to match
     * @param array  $parameters [optional] Any parameters to set for dynamic routes
     * @param int    $status     [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function redirectToRoute($route, array $parameters = [], $status = 302, array $headers = []);
}
