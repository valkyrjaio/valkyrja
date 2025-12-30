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

namespace Valkyrja\Http\Message\Response;

use JsonException;
use Override;
use RuntimeException;
use Valkyrja\Http\Message\Constant\ContentType;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\Contract\JsonResponse as Contract;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidStreamException;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Type\BuiltIn\Support\Arr;

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
     * @var array<array-key, mixed>
     */
    protected const array DEFAULT_DATA = [];

    /**
     * The default encoding options to use for json_encode.
     *
     * @var int
     */
    protected const int DEFAULT_ENCODING_OPTIONS = 79;

    /**
     * NativeJsonResponse constructor.
     *
     * @param array<array-key, mixed> $data            [optional] The data
     * @param StatusCode              $statusCode      [optional] The status
     * @param array<string, string[]> $headers         [optional] The headers
     * @param int                     $encodingOptions [optional] The encoding options
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidStreamException
     * @throws JsonException
     */
    public function __construct(
        protected array $data = self::DEFAULT_DATA,
        StatusCode $statusCode = self::DEFAULT_STATUS_CODE,
        array $headers = self::DEFAULT_HEADERS,
        protected int $encodingOptions = self::DEFAULT_ENCODING_OPTIONS
    ) {
        $body = new Stream();
        $body->write((string) json_encode($data, JSON_THROW_ON_ERROR | $this->encodingOptions));
        $body->rewind();

        parent::__construct(
            $body,
            $statusCode,
            $this->injectHeader(HeaderName::CONTENT_TYPE, ContentType::APPLICATION_JSON, $headers, true)
        );
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public static function createFromData(
        array|null $data = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): static {
        return new static(
            $data ?? static::DEFAULT_DATA,
            $statusCode ?? static::DEFAULT_STATUS_CODE,
            $headers ?? static::DEFAULT_HEADERS,
            static::DEFAULT_ENCODING_OPTIONS
        );
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function getBodyAsJson(): array
    {
        return Arr::fromString((string) $this->getBody());
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function withJsonAsBody(array $data): static
    {
        $body = new Stream();
        $body->write((string) json_encode($data, JSON_THROW_ON_ERROR | $this->encodingOptions));
        $body->rewind();

        return $this->withBody($body);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withCallback(string $callback): static
    {
        $this->verifyCallback($callback);

        // Not using application/javascript for compatibility reasons with older browsers.
        $new = $this->withHeader(HeaderName::CONTENT_TYPE, ContentType::TEXT_JAVASCRIPT);

        $new->stream = new Stream();
        $new->stream->write(sprintf('/**/%s(%s);', $callback, $this->stream->getContents()));
        $new->stream->rewind();
        $this->stream->rewind();

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function withoutCallback(): static
    {
        // Not using application/javascript for compatibility reasons with older browsers.
        $new = $this->withHeader(HeaderName::CONTENT_TYPE, ContentType::APPLICATION_JSON);

        $new->stream = new Stream();
        $new->stream->write((string) json_encode($new->data, JSON_THROW_ON_ERROR | $new->encodingOptions));
        $new->stream->rewind();
        $this->stream->rewind();

        return $new;
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
