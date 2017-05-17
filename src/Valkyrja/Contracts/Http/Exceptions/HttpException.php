<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Http\Exceptions;

use Exception;

/**
 * Interface HttpException.
 *
 * @author Melech Mizrachi
 */
interface HttpException
{
    /**
     * HttpException constructor.
     *
     * @link http://php.net/manual/en/exception.construct.php
     *
     * @param int        $statusCode The status code to use
     * @param string     $message    [optional] The Exception message to throw
     * @param \Exception $previous   [optional] The previous exception used for the exception chaining
     * @param array      $headers    [optional] The headers to send
     * @param int        $code       [optional] The Exception code
     */
    public function __construct(
        int $statusCode,
        string $message = '',
        ?Exception $previous = null,
        array $headers = [],
        int $code = 0
    );

    /**
     * Get the status code for this exception.
     *
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Get the headers set for this exception.
     *
     * @return array
     */
    public function getHeaders(): array;
}
