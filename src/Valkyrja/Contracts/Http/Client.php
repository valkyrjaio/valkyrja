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

/**
 * Interface Client.
 *
 *
 * @author  Melech Mizrachi
 */
interface Client
{
    /**
     * Make a request.
     *
     * @param string $method  The request method
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return object
     */
    public function request(string $method, string $uri, array $options = []);

    /**
     * Make an asynchronous request.
     *
     * @param string $method  The request method
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return object
     */
    public function requestAsync(string $method, string $uri, array $options = []);

    /**
     * Get the host.
     *
     * @return string
     */
    public function getHost(): string;

    /**
     * Set the host.
     *
     * @return string
     */
    public function setHost(): string;

    /**
     * Get the uri with the host prepended.
     *
     * @param string $uri [optional] Uri to append to host.
     *
     * @return string
     */
    public function getUri(string $uri = ''): string;

    /**
     * Make a get request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return object
     */
    public function get(string $uri, array $options = []);

    /**
     * Make an asynchronous get request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return object
     */
    public function getAsync(string $uri, array $options = []);

    /**
     * Make a post request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return object
     */
    public function post(string $uri, array $options = []);

    /**
     * Make an asynchronous post request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return object
     */
    public function postAsync(string $uri, array $options = []);

    /**
     * Make a head request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return object
     */
    public function head(string $uri, array $options = []);

    /**
     * Make an asynchronous head request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return object
     */
    public function headAsync(string $uri, array $options = []);

    /**
     * Make a put request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return object
     */
    public function put(string $uri, array $options = []);

    /**
     * Make an asynchronous put request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return object
     */
    public function putAsync(string $uri, array $options = []);

    /**
     * Make a patch request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return object
     */
    public function patch(string $uri, array $options = []);

    /**
     * Make an asynchronous patch request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return object
     */
    public function patchAsync(string $uri, array $options = []);

    /**
     * Make a delete request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return object
     */
    public function delete(string $uri, array $options = []);

    /**
     * Make an asynchronous delete request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return object
     */
    public function deleteAsync(string $uri, array $options = []);
}
