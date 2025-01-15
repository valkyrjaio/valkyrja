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
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Value\Contract\Cookie;
use Valkyrja\Http\Message\Message;
use Valkyrja\Http\Message\Response\Contract\Response as Contract;
use Valkyrja\Http\Message\Stream\Contract\Stream;
use Valkyrja\Http\Message\Stream\Exception\InvalidStreamException;
use Valkyrja\Http\Message\Stream\Stream as HttpStream;

use function sprintf;

/**
 * Class Response.
 *
 * @author Melech Mizrachi
 *
 * @phpstan-consistent-constructor
 *   Will be overridden if need be
 */
class Response implements Contract
{
    use Message;

    /**
     * The default content to set in the body.
     *
     * @var string
     */
    protected const DEFAULT_CONTENT = '';

    /**
     * The default status code to set.
     *
     * @var StatusCode
     */
    protected const DEFAULT_STATUS_CODE = StatusCode::OK;

    /**
     * The default headers to set.
     *
     * @var array<string, string[]>
     */
    protected const DEFAULT_HEADERS = [];

    /**
     * The status phrase.
     *
     * @var string
     */
    protected string $statusPhrase;

    /**
     * NativeResponse constructor.
     *
     * @param Stream                  $body       [optional] The body
     * @param StatusCode              $statusCode [optional] The status
     * @param array<string, string[]> $headers    [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws InvalidStreamException
     */
    public function __construct(
        Stream $body = new HttpStream(),
        protected StatusCode $statusCode = self::DEFAULT_STATUS_CODE,
        array $headers = self::DEFAULT_HEADERS
    ) {
        $this->statusPhrase = $statusCode->asPhrase();

        $this->setBody($body);
        $this->setHeaders($headers);
    }

    /**
     * @inheritDoc
     */
    public static function create(
        string|null $content = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): static {
        $stream = new HttpStream();
        $stream->write($content ?? static::DEFAULT_CONTENT);
        $stream->rewind();

        return new static(
            $stream,
            $statusCode ?? static::DEFAULT_STATUS_CODE,
            $headers ?? static::DEFAULT_HEADERS
        );
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): StatusCode
    {
        return $this->statusCode;
    }

    /**
     * @inheritDoc
     */
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
    public function getReasonPhrase(): string
    {
        return $this->statusPhrase ?: $this->statusCode->asPhrase();
    }

    /**
     * @inheritDoc
     */
    public function withCookie(Cookie $cookie): static
    {
        return $this->withAddedHeader(HeaderName::SET_COOKIE, (string) $cookie);
    }

    /**
     * @inheritDoc
     */
    public function withoutCookie(Cookie $cookie): static
    {
        return $this->withAddedHeader(HeaderName::SET_COOKIE, (string) $cookie->delete());
    }

    /**
     * @inheritDoc
     */
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
    public function sendHeaders(): static
    {
        foreach ($this->headers as $name => $values) {
            foreach ($values as $value) {
                header("$name: $value", false);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function sendBody(): static
    {
        $stream = $this->stream;

        if ($stream->isSeekable()) {
            $stream->rewind();
        }

        echo $stream->getContents();

        $stream->rewind();

        ob_flush();
        flush();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function send(): static
    {
        $this->sendHttpLine();
        $this->sendHeaders();
        $this->sendBody();

        return $this;
    }
}
