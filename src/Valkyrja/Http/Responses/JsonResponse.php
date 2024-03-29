<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Responses;

use InvalidArgumentException;
use JsonException;
use RuntimeException;
use Valkyrja\Http\Constants\ContentType;
use Valkyrja\Http\Constants\Header;
use Valkyrja\Http\Constants\StreamType;
use Valkyrja\Http\Exceptions\InvalidStatusCode;
use Valkyrja\Http\Exceptions\InvalidStream;
use Valkyrja\Http\JsonResponse as Contract;
use Valkyrja\Http\Streams\Stream;

use function explode;
use function json_encode;
use function preg_match;
use function sprintf;

use const JSON_THROW_ON_ERROR;

/**
 * Class JsonResponse.
 *
 * @author Melech Mizrachi
 */
class JsonResponse extends Response implements Contract
{
    /**
     * The default data to set.
     *
     * @var array
     */
    protected const DEFAULT_DATA = [];

    /**
     * The default encoding options to use for json_encode.
     *
     * @var int
     */
    protected const DEFAULT_ENCODING_OPTIONS = 79;

    /**
     * NativeJsonResponse constructor.
     *
     * @param array $data            [optional] The data
     * @param int   $statusCode      [optional] The status
     * @param array $headers         [optional] The headers
     * @param int   $encodingOptions [optional] The encoding options
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidStatusCode
     * @throws InvalidStream
     * @throws JsonException
     */
    public function __construct(
        protected array $data = self::DEFAULT_DATA,
        int $statusCode = self::DEFAULT_STATUS_CODE,
        array $headers = self::DEFAULT_HEADERS,
        protected int $encodingOptions = self::DEFAULT_ENCODING_OPTIONS
    ) {
        $body = new Stream(StreamType::TEMP, 'wb+');
        $body->write(json_encode($data, JSON_THROW_ON_ERROR | $this->encodingOptions));
        $body->rewind();

        parent::__construct(
            $body,
            $statusCode,
            $this->injectHeader(Header::CONTENT_TYPE, ContentType::APPLICATION_JSON, $headers)
        );
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public static function createFromData(array|null $data = null, int|null $statusCode = null, array|null $headers = null): static
    {
        return new static(
            $data ?? static::DEFAULT_DATA,
            $statusCode ?? static::DEFAULT_STATUS_CODE,
            $headers ?? static::DEFAULT_HEADERS,
            static::DEFAULT_ENCODING_OPTIONS
        );
    }

    /**
     * @inheritDoc
     */
    public function withCallback(string $callback): static
    {
        $this->verifyCallback($callback);
        $this->stream->write(sprintf('/**/%s(%s);', $callback, $this->stream->getContents()));
        $this->stream->rewind();

        // Not using application/javascript for compatibility reasons with older browsers.
        return $this->withHeader(Header::CONTENT_TYPE, ContentType::TEXT_JAVASCRIPT);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function withoutCallback(): static
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
}
