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
 * Interface JsonResponse
 *
 * @package Valkyrja\Contracts\Http
 *
 * @author  Melech Mizrachi
 */
interface JsonResponse extends Response
{
    /**
     * Response constructor.
     *
     * @param string $content [optional] The response content, see setContent()
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     * @param array  $data    [optional] An array of data
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $content = '', int $status = 200, array $headers = [], array $data = []);

    /**
     * Sets the JSONP callback.
     *
     * @param string $callback [optional] The JSONP callback or null to use none
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     *
     * @throws \InvalidArgumentException When the callback name is not valid
     */
    public function setCallback(string $callback = null) : JsonResponse;

    /**
     * Sets a raw string containing a JSON document to be sent.
     *
     * @param string $json The json to set
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function setJson(string $json) : JsonResponse;

    /**
     * Sets the data to be sent as JSON.
     *
     * @param mixed $data [optional] The data to set
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function setData(array $data = []) : JsonResponse;

    /**
     * Returns options used while encoding data to JSON.
     *
     * @return int
     */
    public function getEncodingOptions() : int;

    /**
     * Sets options used while encoding data to JSON.
     *
     * @param int $encodingOptions The encoding options to set
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     */
    public function setEncodingOptions(int $encodingOptions) : JsonResponse;
}
