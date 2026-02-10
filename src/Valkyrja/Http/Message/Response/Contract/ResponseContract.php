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

namespace Valkyrja\Http\Message\Response\Contract;

use InvalidArgumentException;
use Valkyrja\Http\Message\Contract\MessageContract;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Header\Value\Contract\CookieContract;

interface ResponseContract extends MessageContract
{
    /**
     * Create a response.
     *
     * @param string|null           $content    [optional] The response content
     * @param StatusCode|null       $statusCode [optional] The response status code
     * @param HeaderContract[]|null $headers    [optional] An array of response headers
     */
    public static function create(
        string|null $content = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): static;

    /**
     * Get the status code.
     */
    public function getStatusCode(): StatusCode;

    /**
     * Create a new instance with the specified status code and, optionally, reason phrase.
     *
     * @see http://tools.ietf.org/html/rfc7231#section-6
     * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     *
     * @throws InvalidArgumentException For invalid status code arguments
     */
    public function withStatus(StatusCode $code, string|null $reasonPhrase = null): static;

    /**
     * Get the reason phrase.
     *
     * @see http://tools.ietf.org/html/rfc7231#section-6
     * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     */
    public function getReasonPhrase(): string;

    /**
     * Create a new instance with the specified cookie.
     *
     * @throws InvalidArgumentException for invalid header names or values
     */
    public function withCookie(CookieContract $cookie): static;

    /**
     * Create a new instance without the specified cookie.
     *
     * @throws InvalidArgumentException for invalid header names or values
     */
    public function withoutCookie(CookieContract $cookie): static;

    /**
     * Send the response HTTP line header.
     */
    public function sendHttpLine(): static;

    /**
     * Send the response headers.
     */
    public function sendHeaders(): static;

    /**
     * Send the response body.
     */
    public function sendBody(): static;

    /**
     * Send the response.
     */
    public function send(): static;
}
