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

namespace Valkyrja\Client;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface Client.
 *
 * @author Melech Mizrachi
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
     * @return ResponseInterface
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface;

    /**
     * Make a get request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return ResponseInterface
     */
    public function get(string $uri, array $options = []): ResponseInterface;

    /**
     * Make a post request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return ResponseInterface
     */
    public function post(string $uri, array $options = []): ResponseInterface;

    /**
     * Make a head request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return ResponseInterface
     */
    public function head(string $uri, array $options = []): ResponseInterface;

    /**
     * Make a put request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return ResponseInterface
     */
    public function put(string $uri, array $options = []): ResponseInterface;

    /**
     * Make a patch request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return ResponseInterface
     */
    public function patch(string $uri, array $options = []): ResponseInterface;

    /**
     * Make a delete request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return ResponseInterface
     */
    public function delete(string $uri, array $options = []): ResponseInterface;
}
