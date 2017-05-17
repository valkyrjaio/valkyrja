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

use Valkyrja\Http\ResponseCode;

/**
 * Interface JsonResponse.
 *
 * @author Melech Mizrachi
 */
interface JsonResponse extends Response
{
    /**
     * JsonResponse constructor.
     *
     * @param string $content [optional] The response content, see setContent()
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     * @param array  $data    [optional] An array of data
     */
    public function __construct(
        string $content = '',
        int $status = ResponseCode::HTTP_OK,
        array $headers = [],
        array $data = []
    );

    /**
     * Create a new json response.
     *
     * @param string $content [optional] The response content, see setContent()
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     * @param array  $data    [optional] An array of data
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     */
    public static function createJson(
        string $content = '',
        int $status = ResponseCode::HTTP_OK,
        array $headers = [],
        array $data = []
    ): self;

    /**
     * Sets the JSONP callback.
     *
     * @param string $callback [optional] The JSONP callback or null to use none
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     */
    public function setCallback(string $callback = null): self;

    /**
     * Sets a raw string containing a JSON document to be sent.
     *
     * @param string $json The json to set
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     */
    public function setJson(string $json): self;

    /**
     * Sets the data to be sent as JSON.
     *
     * @param mixed $data [optional] The data to set
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     */
    public function setData(array $data = []): self;

    /**
     * Returns options used while encoding data to JSON.
     *
     * @return int
     */
    public function getEncodingOptions(): int;

    /**
     * Sets options used while encoding data to JSON.
     *
     * @param int $encodingOptions The encoding options to set
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     */
    public function setEncodingOptions(int $encodingOptions): self;
}
