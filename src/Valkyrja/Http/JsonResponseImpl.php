<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http;

use Valkyrja\Application;
use Valkyrja\Container\CoreComponent;
use Valkyrja\Support\Providers\Provides;

/**
 * Class JsonResponse.
 *
 * @author Melech Mizrachi
 */
class JsonResponseImpl extends ResponseImpl implements JsonResponse
{
    use Provides;

    /**
     * @constant
     *
     * Encode <, >, ', &, and " characters in the JSON, making it also safe to be embedded into HTML.
     * 15 === JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
     */
    public const DEFAULT_ENCODING_OPTIONS = 15;

    /**
     * Json data.
     *
     * @var string
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
     * JsonResponse constructor.
     *
     * @param string $content [optional] The response content, see setContent()
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     * @param array  $data    [optional] An array of data
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        string $content = '',
        int $status = StatusCode::OK,
        array $headers = [],
        array $data = []
    ) {
        parent::__construct($content, $status, $headers);

        // Set the json data
        $this->setData($data);
    }

    /**
     * Create a new json response.
     *
     * @param string $content [optional] The response content, see setContent()
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     * @param array  $data    [optional] An array of data
     *
     * @throws \InvalidArgumentException
     *
     * @return \Valkyrja\Http\JsonResponse
     */
    public static function createJson(
        string $content = '',
        int $status = StatusCode::OK,
        array $headers = [],
        array $data = []
    ): JsonResponse {
        return new static($content, $status, $headers, $data);
    }

    /**
     * Sets the JSONP callback.
     *
     * @param string $callback [optional] The JSONP callback or null to use none
     *
     * @throws \InvalidArgumentException When the callback name is not valid
     *
     * @return \Valkyrja\Http\JsonResponse
     */
    public function setCallback(string $callback = null): JsonResponse
    {
        if (null !== $callback) {
            // taken from http://www.geekality.net/2011/08/03/valid-javascript-identifier/
            $pattern = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';
            $parts   = explode('.', $callback);

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
     * @throws \InvalidArgumentException
     *
     * @return \Valkyrja\Http\JsonResponse
     */
    public function setJson(string $json): JsonResponse
    {
        $this->data = $json;

        return $this->update();
    }

    /**
     * Sets the data to be sent as JSON.
     *
     * @param array $data [optional] The data to set
     *
     * @throws \InvalidArgumentException
     *
     * @return \Valkyrja\Http\JsonResponse
     */
    public function setData(array $data = []): JsonResponse
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
    public function getEncodingOptions(): int
    {
        return $this->encodingOptions;
    }

    /**
     * Sets options used while encoding data to JSON.
     *
     * @param int $encodingOptions The encoding options to set
     *
     * @throws \InvalidArgumentException
     *
     * @return \Valkyrja\Http\JsonResponse
     */
    public function setEncodingOptions(int $encodingOptions): JsonResponse
    {
        $this->encodingOptions = $encodingOptions;

        return $this->setData(json_decode($this->data));
    }

    /**
     * Updates the content and headers according to the JSON data and callback.
     *
     * @return \Valkyrja\Http\JsonResponse
     */
    protected function update(): JsonResponse
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

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            CoreComponent::JSON_RESPONSE,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Application $app The application
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::JSON_RESPONSE,
            new static()
        );
    }
}
