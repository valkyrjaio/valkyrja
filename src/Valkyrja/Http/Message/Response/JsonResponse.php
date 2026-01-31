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
use Valkyrja\Http\Message\Constant\ContentTypeValue;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\ContentType;
use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Response\Contract\JsonResponseContract;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidStreamException;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Type\BuiltIn\Support\Arr;

use function explode;
use function json_encode;
use function preg_match;
use function sprintf;

use const JSON_THROW_ON_ERROR;

class JsonResponse extends Response implements JsonResponseContract
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
     * @param array<array-key, mixed> $data            [optional] The data
     * @param StatusCode              $statusCode      [optional] The status
     * @param HeaderContract[]        $headers         [optional] The headers
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

        $this->setHeaders(...$headers);

        parent::__construct(
            $body,
            $statusCode,
            $this->injectHeader(new Header(HeaderName::CONTENT_TYPE, ContentTypeValue::APPLICATION_JSON), $this->headers, true)
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
        $new = $this->withHeader(new ContentType(ContentTypeValue::TEXT_JAVASCRIPT));

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
        $new = $this->withHeader(new ContentType(ContentTypeValue::APPLICATION_JSON));

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
