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
     * @param mixed $data    The response content, see setContent()
     * @param int   $status  The response status code
     * @param array $headers An array of response headers
     */
    public function __construct($data = null, $status = 200, $headers = []);

    /**
     * Sets the JSONP callback.
     *
     * @param string|null $callback The JSONP callback or null to use none
     *
     * @return JsonResponse
     *
     * @throws \InvalidArgumentException When the callback name is not valid
     */
    public function setCallback($callback = null);

    /**
     * Sets a raw string containing a JSON document to be sent.
     *
     * @param string $json The json to set
     *
     * @return JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function setJson($json);

    /**
     * Sets the data to be sent as JSON.
     *
     * @param mixed $data The data to set
     *
     * @return JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function setData($data = []);

    /**
     * Returns options used while encoding data to JSON.
     *
     * @return int
     */
    public function getEncodingOptions();

    /**
     * Sets options used while encoding data to JSON.
     *
     * @param int $encodingOptions The encoding options to set
     *
     * @return JsonResponse
     */
    public function setEncodingOptions($encodingOptions);
}
