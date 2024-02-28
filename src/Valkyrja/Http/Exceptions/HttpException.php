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

namespace Valkyrja\Http\Exceptions;

use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Http\Response;

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
     * @var int
     */
    protected int $statusCode = StatusCode::INTERNAL_SERVER_ERROR;

    /**
     * The headers for this exception.
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * The response to send for this exception.
     *
     * @var Response|null
     */
    protected Response|null $response = null;

    /**
     * HttpException constructor.
     *
     * @see http://php.net/manual/en/exception.construct.php
     *
     * @param int|null      $statusCode [optional] The status code to use
     * @param string|null   $message    [optional] The Exception message to throw
     * @param array|null    $headers    [optional] The headers to send
     * @param Response|null $response   [optional] The Response to send
     */
    public function __construct(
        int|null $statusCode = null,
        string|null $message = null,
        array|null $headers = null,
        Response|null $response = null
    ) {
        $this->statusCode = $statusCode ?? StatusCode::INTERNAL_SERVER_ERROR;
        $this->headers    = $headers ?? [];
        $this->response   = $response;

        parent::__construct($message ?? '');
    }

    /**
     * Get the status code for this exception.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the headers set for this exception.
     *
     * @return array
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
