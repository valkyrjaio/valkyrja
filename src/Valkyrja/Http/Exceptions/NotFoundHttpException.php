<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Exceptions;

use Exception;
use Valkyrja\Http\Enums\StatusCode;

/**
 * Class NotFoundHttpException.
 *
 * @author Melech Mizrachi
 */
class NotFoundHttpException extends HttpException
{
    /**
     * NotFoundHttpException constructor.
     *
     * @param int       $statusCode [optional] The status code to use
     * @param string    $message    [optional] The Exception message to throw
     * @param Exception $previous   [optional] The previous exception used for the exception chaining
     * @param array     $headers    [optional] The headers to send
     * @param int       $code       [optional] The Exception code
     */
    public function __construct(
        int $statusCode = StatusCode::NOT_FOUND,
        string $message = '',
        Exception $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
