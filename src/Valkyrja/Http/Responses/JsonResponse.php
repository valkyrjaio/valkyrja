<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Responses;

use InvalidArgumentException;
use RuntimeException;
use Valkyrja\Application\Application;
use Valkyrja\Http\Enums\ContentType;
use Valkyrja\Http\Enums\Header;
use Valkyrja\Http\Enums\Stream as StreamEnum;
use Valkyrja\Http\Exceptions\InvalidStatusCode;
use Valkyrja\Http\Exceptions\InvalidStream;
use Valkyrja\Http\JsonResponse as JsonResponseContract;
use Valkyrja\Http\Streams\Stream;

use const JSON_THROW_ON_ERROR;

/**
 * Class JsonResponse.
 *
 * @author Melech Mizrachi
 */
class JsonResponse extends Response implements JsonResponseContract
{
    /**
     * The default encoding options to use for json_encode().
     *
     * @constant int
     */
    protected const DEFAULT_ENCODING_OPTIONS = 79;

    /**
     * The json data.
     *
     * @var array
     */
    protected array $data;

    /**
     * Encoding options.
     *
     * @var int
     */
    protected int $encodingOptions;

    /**
     * NativeJsonResponse constructor.
     *
     * @param array|null $data            [optional] The data
     * @param int|null   $status          [optional] The status
     * @param array|null $headers         [optional] The headers
     * @param int|null   $encodingOptions [optional] The encoding options
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidStatusCode
     * @throws InvalidStream
     */
    public function __construct(
        array $data = null,
        int $status = null,
        array $headers = null,
        int $encodingOptions = null
    ) {
        parent::__construct();

        $this->initializeJson($data, $status, $headers, $encodingOptions);
    }

    /**
     * Initialize a json response.
     *
     * @param array|null $data            [optional] The data
     * @param int|null   $status          [optional] The status
     * @param array|null $headers         [optional] The headers
     * @param int|null   $encodingOptions [optional] The encoding options
     *
     * @return void
     */
    protected function initializeJson(
        array $data = null,
        int $status = null,
        array $headers = null,
        int $encodingOptions = null
    ): void {
        $this->data            = $data ?? [];
        $this->encodingOptions = $encodingOptions ?? static::DEFAULT_ENCODING_OPTIONS;

        $body = new Stream(StreamEnum::TEMP, 'wb+');
        $body->write(json_encode($this->data, JSON_THROW_ON_ERROR | $this->encodingOptions));
        $body->rewind();

        parent::__construct(
            $body,
            $status,
            $this->injectHeader(Header::CONTENT_TYPE, ContentType::APPLICATION_JSON, $headers)
        );
    }

    /**
     * Make a new json response.
     *
     * @param array|null $data            [optional] The data
     * @param int|null   $status          [optional] The status
     * @param array|null $headers         [optional] The headers
     * @param int|null   $encodingOptions [optional] The encoding options
     *
     * @return static
     */
    public static function makeJson(
        array $data = null,
        int $status = null,
        array $headers = null,
        int $encodingOptions = null
    ): self {
        $response = new static();

        $response->initializeJson($data, $status, $headers, $encodingOptions);

        return $response;
    }

    /**
     * With callback.
     *
     * @param string $callback The callback
     *
     * @return static
     */
    public function withCallback(string $callback): self
    {
        $this->verifyCallback($callback);
        $this->stream->write(sprintf('/**/%s(%s);', $callback, $this->stream->getContents()));
        $this->stream->rewind();

        // Not using application/javascript for compatibility reasons with older browsers.
        return $this->withHeader(Header::CONTENT_TYPE, ContentType::TEXT_JAVASCRIPT);
    }

    /**
     * Without callback.
     *
     * @return static
     */
    public function withoutCallback(): self
    {
        $this->stream->write(json_encode($this->data, JSON_THROW_ON_ERROR | $this->encodingOptions));
        $this->stream->rewind();

        // Not using application/javascript for compatibility reasons with older browsers.
        return $this->withHeader(Header::CONTENT_TYPE, ContentType::APPLICATION_JSON);
    }

    /**
     * Verify a callback.
     *
     * @param string $callback The callback
     *
     * @return void
     */
    protected function verifyCallback(string $callback): void
    {
        // taken from http://www.geekality.net/2011/08/03/valid-javascript-identifier/
        $pattern = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';

        foreach (explode('.', $callback) as $part) {
            if (! preg_match($pattern, $part)) {
                throw new InvalidArgumentException(
                    'The callback name is not valid.'
                );
            }
        }
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            JsonResponseContract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->setSingleton(JsonResponseContract::class, new static());
    }
}
