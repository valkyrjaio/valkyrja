<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamInvalidStreamException;
use Valkyrja\Http\Message\Trait\Message;

use function flush;
use function header;
use function ob_flush;
use function ob_get_level;
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
     * @throws HttpStreamInvalidStreamException
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
    public function withStatusCode(StatusCode $code): static
    {
        $new = clone $this;

        $new->statusCode   = $code;
        $new->statusPhrase = $code->asPhrase();

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getReasonPhrase(): string
    {
        return $this->statusPhrase;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withReasonPhrase(string $reasonPhrase): static
    {
        $new = clone $this;

        $new->statusPhrase = $reasonPhrase ?: $this->statusCode->asPhrase();

        return $new;
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

        $this->header($httpLine, true, $this->statusCode->value);

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function sendHeaders(): static
    {
        foreach ($this->headers->getAll() as $header) {
            $this->header($header->__toString(), false);
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

        if ($this->obGetLevel() > 0) {
            $this->obFlush();
        }

        $this->flush();

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

    /**
     * Send a raw HTTP header.
     *
     * Wraps the native header() call in an overridable seam so tests can
     * intercept it without resorting to namespace function shadowing.
     */
    protected function header(string $header, bool $replace = true, int $responseCode = 0): void
    {
        header($header, $replace, $responseCode);
    }

    /**
     * Get the current output buffering level.
     */
    protected function obGetLevel(): int
    {
        return ob_get_level();
    }

    /**
     * Flush the output buffer.
     */
    protected function obFlush(): void
    {
        ob_flush();
    }

    /**
     * Flush the system output buffer.
     */
    protected function flush(): void
    {
        flush();
    }
}
