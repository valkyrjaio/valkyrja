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

use \Valkyrja\Contracts\Http\JsonResponse as JsonResponseContract;

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
     * @inheritdoc
     */
    public function __construct($data = null, $status = 200, $headers = [])
    {
        parent::__construct('', $status, $headers);

        if (null === $data) {
            $data = new \ArrayObject();
        }

        is_array($data)
            ? $this->setData($data)
            : $this->setJson($data);
    }

    /**
     * @inheritdoc
     */
    public function setCallback($callback = null)
    {
        if (null !== $callback) {
            // taken from http://www.geekality.net/2011/08/03/valid-javascript-identifier/
            $pattern = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';
            $parts = explode('.', $callback);

            foreach ($parts as $part) {
                if (!preg_match($pattern, $part)) {
                    throw new \InvalidArgumentException('The callback name is not valid.');
                }
            }
        }

        $this->callback = $callback;

        return $this->update();
    }

    /**
     * @inheritdoc
     */
    public function setJson($json)
    {
        $this->data = $json;

        return $this->update();
    }

    /**
     * @inheritdoc
     */
    public function setData($data = [])
    {

        $data = json_encode($data, $this->encodingOptions);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        return $this->setJson($data);
    }

    /**
     * @inheritdoc
     */
    public function getEncodingOptions()
    {
        return $this->encodingOptions;
    }

    /**
     * @inheritdoc
     */
    public function setEncodingOptions($encodingOptions)
    {
        $this->encodingOptions = (int) $encodingOptions;

        return $this->setData(json_decode($this->data));
    }

    /**
     * Updates the content and headers according to the JSON data and callback.
     *
     * @return JsonResponse
     */
    protected function update()
    {
        if (null !== $this->callback) {
            // Not using application/javascript for compatibility reasons with older browsers.
            $this->setHeader('Content-Type', 'text/javascript');

            return $this->setContent(sprintf('/**/%s(%s);', $this->callback, $this->data));
        }

        // Only set the header when there is none or when it equals 'text/javascript' (from a previous update with callback)
        // in order to not overwrite a custom definition.
        if (!$this->hasHeader('Content-Type') || 'text/javascript' === $this->getHeader('Content-Type')) {
            $this->setHeader('Content-Type', 'application/json');
        }

        return $this->setContent($this->data);
    }
}
