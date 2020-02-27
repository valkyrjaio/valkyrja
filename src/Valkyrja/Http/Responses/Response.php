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
use Valkyrja\Application\Application;
use Valkyrja\Http\Cookie;
use Valkyrja\Http\Enums\Header;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Enums\Stream as StreamEnum;
use Valkyrja\Http\Exceptions\InvalidStatusCode;
use Valkyrja\Http\Exceptions\InvalidStream;
use Valkyrja\Http\Messages\MessageTrait;
use Valkyrja\Http\Response as ResponseContract;
use Valkyrja\Http\Stream;
use Valkyrja\Http\Streams\Stream as HttpStream;
use Valkyrja\Support\Providers\Provides;

/**
 * Representation of an outgoing, server-side response.
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 * - Protocol version
 * - Status code and reason phrase
 * - Headers
 * - Message body
 * Responses are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 *
 * @author Melech Mizrachi
 */
class Response implements ResponseContract
{
    use MessageTrait;
    use Provides;

    /**
     * The status code.
     *
     * @var int
     */
    protected int $statusCode;

    /**
     * The status phrase.
     *
     * @var string
     */
    protected string $statusPhrase;

    /**
     * NativeResponse constructor.
     *
     * @param Stream|null $body       [optional] The body
     * @param int|null    $statusCode [optional] The status
     * @param array|null  $headers    [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws InvalidStatusCode
     * @throws InvalidStream
     */
    public function __construct(Stream $body = null, int $statusCode = null, array $headers = null)
    {
        $this->initialize($body, $statusCode, $headers);
    }

    /**
     * Initialize a response.
     *
     * @param Stream|null $body       [optional] The body
     * @param int|null    $statusCode [optional] The status
     * @param array|null  $headers    [optional] The headers
     *
     * @return void
     */
    protected function initialize(Stream $body = null, int $statusCode = null, array $headers = null): void
    {
        $this->stream       = $body ?? new HttpStream(StreamEnum::INPUT, 'rw');
        $this->statusCode   = $this->validateStatusCode($statusCode ?? StatusCode::OK);
        $this->statusPhrase = StatusCode::TEXTS[$this->statusCode];

        $this->setHeaders($headers ?? []);
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
        if (StatusCode::MIN > $code || $code > StatusCode::MAX) {
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

    /**
     * Create a response.
     *
     * @param string|null $content    [optional] The body
     * @param int|null    $statusCode [optional] The status
     * @param array|null  $headers    [optional] The headers
     *
     * @return static
     */
    public static function createResponse(string $content = null, int $statusCode = null, array $headers = null): self
    {
        $response = new static();

        $stream = new HttpStream(StreamEnum::TEMP, 'wb+');
        $stream->write($content ?? '');
        $stream->rewind();

        $response->initialize($stream, $statusCode, $headers);

        return $response;
    }

    /**
     * Gets the response status code.
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Return an instance with the specified status code and, optionally,
     * reason phrase.
     * If no reason phrase is specified, implementations MAY choose to default
     * to the RFC 7231 or IANA recommended reason phrase for the response's
     * status code.
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated status and reason phrase.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     *
     * @param int    $code         The 3-digit integer result code to set.
     * @param string $reasonPhrase The reason phrase to use with the
     *                             provided status code; if none is provided,
     *                             implementations MAY use the defaults as
     *                             suggested in the HTTP specification.
     *
     * @throws InvalidStatusCode For invalid status code arguments.
     *
     * @return static
     */
    public function withStatus(int $code, string $reasonPhrase = null): self
    {
        $new = clone $this;

        $new->statusCode   = $new->validateStatusCode($code);
        $new->statusPhrase = $reasonPhrase ?? StatusCode::TEXTS[$this->statusCode];

        return $new;
    }

    /**
     * Gets the response reason phrase associated with the status code.
     * Because a reason phrase is not a required element in a response
     * status line, the reason phrase value MAY be null. Implementations MAY
     * choose to return the default RFC 7231 recommended reason phrase (or
     * those listed in the IANA HTTP Status Code Registry) for the response's
     * status code.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     *
     * @return string Reason phrase; must return an empty string if none
     *                present.
     */
    public function getReasonPhrase(): string
    {
        return $this->statusPhrase ?: StatusCode::TEXTS[$this->statusCode];
    }

    /**
     * Return an instance with the specified cookie appended with the given
     * value.
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list. If the cookie header
     * did not exist previously, it will be added.
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new cookie header and/or value.
     *
     * @param Cookie $cookie The cookie model
     *
     * @throws InvalidArgumentException for invalid header names or values.
     *
     * @return static
     */
    public function withCookie(Cookie $cookie): self
    {
        return $this->withAddedHeader(Header::SET_COOKIE, (string) $cookie);
    }

    /**
     * Return an instance with the specified cookie appended to the
     * Set-Cookie header as expired.
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list. If the cookie header
     * did not exist previously, it will be added.
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new cookie header and/or value.
     *
     * @param Cookie $cookie The cookie model
     *
     * @throws InvalidArgumentException for invalid header names or values.
     *
     * @return static
     */
    public function withoutCookie(Cookie $cookie): self
    {
        $cookie->setValue();
        $cookie->setExpire(0);

        return $this->withAddedHeader(Header::SET_COOKIE, (string) $cookie);
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            ResponseContract::class,
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
        $app->container()->setSingleton(ResponseContract::class, new static());
    }
}
