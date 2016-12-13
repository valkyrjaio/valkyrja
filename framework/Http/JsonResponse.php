<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based off work by Igor Wiedler for symfony/http-foundation/JsonResponse.php
 */

namespace Valkyrja\Http;

use Valkyrja\Contracts\Http\JsonResponse as JsonResponseContract;

/**
 * Class JsonResponse
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
class JsonResponse extends Response implements JsonResponseContract
{
    /**
     * @constant
     *
     * Encode <, >, ', &, and " characters in the JSON, making it also safe to be embedded into HTML.
     * 15 === JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
     */
    const DEFAULT_ENCODING_OPTIONS = 15;

    /**
     * Json data.
     *
     * @var mixed
     */
    protected $data;

    /**
     * JsonP callback.
     *
     * @var string
     */
    protected $callback;

    /**
     * Json encoding options.
     *
     * @var int
     */
    protected $encodingOptions = self::DEFAULT_ENCODING_OPTIONS;

    /**
     * Response constructor.
     *
     * @param string $data    [optional] The response content, see setContent()
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     */
    public function __construct(string $data = null, int $status = 200, array $headers = [])
    {
        parent::__construct($data, $status, $headers);
    }

    /**
     * Sets the JSONP callback.
     *
     * @param string $callback [optional] The JSONP callback or null to use none
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     *
     * @throws \InvalidArgumentException When the callback name is not valid
     */
    public function setCallback(string $callback = null) : JsonResponseContract
    {
        if (null !== $callback) {
            // taken from http://www.geekality.net/2011/08/03/valid-javascript-identifier/
            $pattern = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';
            $parts = explode('.', $callback);

            foreach ($parts as $part) {
                if (! preg_match($pattern, $part)) {
                    throw new \InvalidArgumentException('The callback name is not valid.');
                }
            }
        }

        $this->callback = $callback;

        return $this->update();
    }

    /**
     * Sets a raw string containing a JSON document to be sent.
     *
     * @param string $json The json to set
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function setJson(string $json) : JsonResponseContract
    {
        $this->data = $json;

        return $this->update();
    }

    /**
     * Sets the data to be sent as JSON.
     *
     * @param array $data [optional] The data to set
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function setData(array $data = []) : JsonResponseContract
    {
        $content = json_encode($data, $this->encodingOptions);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        return $this->setJson($content);
    }

    /**
     * Returns options used while encoding data to JSON.
     *
     * @return int
     */
    public function getEncodingOptions() : int
    {
        return $this->encodingOptions;
    }

    /**
     * Sets options used while encoding data to JSON.
     *
     * @param int $encodingOptions The encoding options to set
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function setEncodingOptions(int $encodingOptions) : JsonResponseContract
    {
        $this->encodingOptions = $encodingOptions;

        return $this->setData(json_decode($this->data));
    }

    /**
     * Updates the content and headers according to the JSON data and callback.
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     */
    protected function update() : JsonResponseContract
    {
        if (null !== $this->callback) {
            // Not using application/javascript for compatibility reasons with older browsers.
            $this->headers()->get('Content-Type', 'text/javascript');

            $this->setContent(sprintf('/**/%s(%s);', $this->callback, $this->data));

            return $this;
        }

        // Only set the header when there is none or when it equals 'text/javascript' (from a previous update with callback)
        // in order to not overwrite a custom definition.
        if (! $this->headers()->has('Content-Type') || 'text/javascript' === $this->headers()->get('Content-Type')) {
            $this->headers()->set('Content-Type', 'application/json');
        }

        $this->setContent($this->data);

        return $this;
    }
}
