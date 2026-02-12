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

namespace Valkyrja\Http\Message\Throwable\Exception;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;

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
     */
    protected HeaderCollectionContract $headers;

    /**
     * @see http://php.net/manual/en/exception.construct.php
     *
     * @param string|null $message [optional] The Exception message to throw
     */
    public function __construct(
        StatusCode|null $statusCode = null,
        string|null $message = null,
        HeaderCollectionContract|null $headers = null,
        protected ResponseContract|null $response = null
    ) {
        $this->statusCode = $statusCode
            ?? $response?->getStatusCode()
            ?? StatusCode::INTERNAL_SERVER_ERROR;
        $this->headers    = $headers ?? new HeaderCollection();
        $this->response   = $response?->withStatus($this->statusCode);

        parent::__construct($message ?? '');
    }

    /**
     * Get the status code for this exception.
     */
    public function getStatusCode(): StatusCode
    {
        return $this->statusCode;
    }

    /**
     * Get the headers set for this exception.
     */
    public function getHeaders(): HeaderCollectionContract
    {
        return $this->headers;
    }

    /**
     * Get the response for this exception.
     */
    public function getResponse(): ResponseContract|null
    {
        return $this->response;
    }
}
