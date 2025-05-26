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

namespace Valkyrja\Http\Message\Exception;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Class HttpException.
 *
 * @author Melech Mizrachi
 */
class HttpException extends RuntimeException
{
    /**
     * The status code for this exception.
     *
     * @var StatusCode
     */
    protected StatusCode $statusCode = StatusCode::INTERNAL_SERVER_ERROR;

    /**
     * The headers for this exception.
     *
     * @var array<string, string[]>
     */
    protected array $headers = [];

    /**
     * HttpException constructor.
     *
     * @see http://php.net/manual/en/exception.construct.php
     *
     * @param StatusCode|null              $statusCode [optional] The status code to use
     * @param string|null                  $message    [optional] The Exception message to throw
     * @param array<string, string[]>|null $headers    [optional] The headers to send
     * @param Response|null                $response   [optional] The Response to send
     */
    public function __construct(
        StatusCode|null $statusCode = null,
        string|null $message = null,
        array|null $headers = [],
        protected Response|null $response = null
    ) {
        $this->statusCode = $statusCode
            ?? $response?->getStatusCode()
            ?? StatusCode::INTERNAL_SERVER_ERROR;
        $this->headers    = $headers ?? [];
        $this->response   = $response?->withStatus($this->statusCode);

        parent::__construct($message ?? '');
    }

    /**
     * Get the status code for this exception.
     *
     * @return StatusCode
     */
    public function getStatusCode(): StatusCode
    {
        return $this->statusCode;
    }

    /**
     * Get the headers set for this exception.
     *
     * @return array<string, string[]>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get the response for this exception.
     *
     * @return Response|null
     */
    public function getResponse(): Response|null
    {
        return $this->response;
    }
}
