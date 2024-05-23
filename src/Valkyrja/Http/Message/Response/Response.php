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
use Valkyrja\Http\Message\Constant\Header;
use Valkyrja\Http\Message\Constant\StatusCode;
use Valkyrja\Http\Message\Constant\StreamType;
use Valkyrja\Http\Message\Exception\InvalidStatusCode;
use Valkyrja\Http\Message\Exception\InvalidStream;
use Valkyrja\Http\Message\Message;
use Valkyrja\Http\Message\Model\Contract\Cookie;
use Valkyrja\Http\Message\Response\Contract\Response as Contract;
use Valkyrja\Http\Message\Stream\Contract\Stream;
use Valkyrja\Http\Message\Stream\Stream as HttpStream;

use function header;
use function sprintf;

/**
 * Class Response.
 *
 * @author Melech Mizrachi
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
     * @var int
     */
    protected const DEFAULT_STATUS_CODE = StatusCode::OK;

    /**
     * The default headers to set.
     *
     * @var array
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
     * @param Stream $body       [optional] The body
     * @param int    $statusCode [optional] The status
     * @param array  $headers    [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws InvalidStatusCode
     * @throws InvalidStream
     */
    public function __construct(
        Stream $body = new HttpStream(StreamType::INPUT, 'rw'),
        protected int $statusCode = self::DEFAULT_STATUS_CODE,
        array $headers = self::DEFAULT_HEADERS
    ) {
        $this->statusCode   = $this->validateStatusCode($statusCode);
        $this->statusPhrase = StatusCode::TEXTS[$this->statusCode];

        $this->setBody($body);
        $this->setHeaders($headers);
    }

    /**
     * @inheritDoc
     */
    public static function create(
        string|null $content = null,
        int|null $statusCode = null,
        array|null $headers = null
    ): static {
        $stream = new HttpStream(StreamType::TEMP, 'wb+');
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
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @inheritDoc
     */
    public function withStatus(int $code, string|null $reasonPhrase = null): static
    {
        $new = clone $this;

        $new->statusCode   = $new->validateStatusCode($code);
        $new->statusPhrase = $reasonPhrase ?? StatusCode::TEXTS[$this->statusCode];

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase(): string
    {
        return $this->statusPhrase ?: StatusCode::TEXTS[$this->statusCode];
    }

    /**
     * @inheritDoc
     */
    public function withCookie(Cookie $cookie): static
    {
        return $this->withAddedHeader(Header::SET_COOKIE, (string) $cookie);
    }

    /**
     * @inheritDoc
     */
    public function withoutCookie(Cookie $cookie): static
    {
        $cookie->setValue();
        $cookie->setExpire(0);

        return $this->withAddedHeader(Header::SET_COOKIE, (string) $cookie);
    }

    /**
     * @inheritDoc
     */
    public function sendHttpLine(): static
    {
        $httpLine = sprintf(
            'HTTP/%s %s %s',
            $this->protocol,
            $this->statusCode,
            $this->statusPhrase
        );

        header($httpLine, true, $this->statusCode);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function sendHeaders(): static
    {
        foreach ($this->headers as $name => $values) {
            /** @var array $values */
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

    /**
     * Validate a status code.
     *
     * @param int $code The code
     *
     * @throws InvalidStatusCode
     *
     * @return int
     */
    protected function validateStatusCode(int $code): int
    {
        if (! StatusCode::isValid($code)) {
            throw new InvalidStatusCode(
                sprintf(
                    'Invalid status code "%d"; must adhere to values set in the %s enum class.',
                    $code,
                    StatusCode::class
                )
            );
        }

        return $code;
    }
}
