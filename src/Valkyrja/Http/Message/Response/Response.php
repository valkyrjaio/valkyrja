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

use InvalidArgumentException;
use Override;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\SetCookie;
use Valkyrja\Http\Message\Header\Value\Contract\CookieContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidStreamException;
use Valkyrja\Http\Message\Trait\Message;

use function sprintf;

class Response implements ResponseContract
{
    use Message;

    /**
     * The status phrase.
     *
     * @var string
     */
    protected string $statusPhrase;

    /**
     * @throws InvalidArgumentException
     * @throws InvalidStreamException
     */
    public function __construct(
        StreamContract $body = new Stream(),
        protected StatusCode $statusCode = StatusCode::OK,
        protected HeaderCollectionContract $headers = new HeaderCollection()
    ) {
        $this->statusPhrase = $statusCode->asPhrase();

        $this->setBody($body);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function create(
        string|null $content = null,
        StatusCode|null $statusCode = null,
        HeaderCollectionContract|null $headers = null
    ): static {
        $stream = new Stream();
        $stream->write($content ?? '');
        $stream->rewind();

        return new static(
            $stream,
            $statusCode ?? StatusCode::OK,
            $headers ?? new HeaderCollection()
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getStatusCode(): StatusCode
    {
        return $this->statusCode;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withStatus(StatusCode $code, string|null $reasonPhrase = null): static
    {
        $new = clone $this;

        $new->statusCode   = $code;
        $new->statusPhrase = $reasonPhrase ?? $code->asPhrase();

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getReasonPhrase(): string
    {
        return $this->statusPhrase ?: $this->statusCode->asPhrase();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withCookie(CookieContract $cookie): static
    {
        $headers = $this->headers->withAddedHeaders(new SetCookie($cookie));

        return $this->withHeaders($headers);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutCookie(CookieContract $cookie): static
    {
        $headers = $this->headers->withAddedHeaders(new SetCookie($cookie->delete()));

        return $this->withHeaders($headers);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function sendHttpLine(): static
    {
        $httpLine = sprintf(
            'HTTP/%s %s %s',
            $this->protocolVersion->value,
            $this->statusCode->value,
            $this->statusPhrase ?: $this->statusCode->asPhrase()
        );

        header($httpLine, true, $this->statusCode->value);

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function sendHeaders(): static
    {
        foreach ($this->headers->getAll() as $header) {
            header($header->__toString(), false);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function sendBody(): static
    {
        $stream = $this->stream;

        if ($stream->isSeekable()) {
            $stream->rewind();
        }

        echo $stream->getContents();

        $stream->rewind();

        if (ob_get_level() > 0) {
            ob_flush();
        }

        flush();

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function send(): static
    {
        $this->sendHttpLine();
        $this->sendHeaders();
        $this->sendBody();

        return $this;
    }
}
