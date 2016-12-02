<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based off work by Taylor Otwell for Illuminate/routing/ResponseFactory.php
 */

namespace Valkyrja\Http;

use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Http\ResponseBuilder as ResponseBuilderContract;
use Valkyrja\Contracts\View\View;

/**
 * Class ResponseBuilder
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
class ResponseBuilder implements ResponseBuilderContract
{
    /**
     * The view factory instance.
     *
     * @var View
     */
    protected $view;

    /**
     * The response factory instance.
     *
     * @var \Valkyrja\Contracts\Http\Response
     */
    protected $response;

    /**
     * ResponseBuilder constructor.
     *
     * @param View                              $view     The View class to use
     * @param \Valkyrja\Contracts\Http\Response $response The Response class to use
     */
    public function __construct(ResponseContract $response, View $view)
    {
        $this->response = $response;
        $this->view = $view;
    }

    /**
     * Make a new instance of Response.
     *
     * @param string $content [optional] The response content
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function make(string $content = '', int $status = 200, array $headers = []) : ResponseContract
    {
        return $this->response->create($content, $status, $headers);
    }

    /**
     * View response builder.
     *
     * @param string $template The view template to use
     * @param array  $data     [optional] The view data
     * @param int    $status   [optional] The response status code
     * @param array  $headers  [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function view(string $template, array $data = [], int $status = 200, array $headers = []) : ResponseContract
    {
        $content = $this->view->make($template, $data)
                              ->render();

        return $this->make($content, $status, $headers);
    }

    /**
     * Json response builder.
     *
     * @param array $data    [optional] The data to set
     * @param int   $status  [optional] The response status code
     * @param array $headers [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function json(array $data = [], int $status = 200, array $headers = [], $options = 0) : ResponseContract
    {
        return $this->response;
    }

    /**
     * JsonP response builder.
     *
     * @param string $callback The jsonp callback
     * @param array  $data     [optional] The data to set
     * @param int    $status   [optional] The response status code
     * @param array  $headers  [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function jsonp(string $callback, array $data = [], int $status = 200, array $headers = [], $options = 0) : ResponseContract
    {
        return $this->response;
    }

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
    public function redirectTo(string $path, array $parameters = [], int $status = 302, array $headers = []) : ResponseContract
    {
        return $this->response;
    }

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
    public function redirectToRoute(string $route, array $parameters = [], int $status = 302, array $headers = []) : ResponseContract
    {
        return $this->response;
    }
}
