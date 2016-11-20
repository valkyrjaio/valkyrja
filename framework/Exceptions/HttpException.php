<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Exceptions;

use Exception;
use RuntimeException;

use Valkyrja\Contracts\Exceptions\HttpException as HttpExceptionContract;

/**
 * Class Exception
 *
 * @package Valkyrja\Exceptions
 *
 * @author  Melech Mizrachi
 */
class HttpException extends RuntimeException implements HttpExceptionContract
{
    /**
     * The status code for this exception.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * The headers for this exception.
     *
     * @var array
     */
    protected $headers;

    /**
     * The view for this exception.
     *
     * @var string
     */
    protected $view;

    /**
     * HttpException constructor.
     *
     * @link http://php.net/manual/en/exception.construct.php
     *
     * @param int        $statusCode The status code to use
     * @param string     $message    [optional] The Exception message to throw
     * @param \Exception $previous   [optional] The previous exception used for the exception chaining
     * @param array      $headers    [optional] The headers to send
     * @param string     $view       [optional] The view template name to use
     * @param int        $code       [optional] The Exception code
     */
    public function __construct(
        $statusCode,
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $view = null,
        $code = 0
    ) {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->view = $view;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the status code for this exception.
     *
     * @return int
     */
    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    /**
     * Get the headers set for this exception.
     *
     * @return array
     */
    public function getHeaders() : array
    {
        return $this->headers;
    }

    /**
     * Get the headers set for this exception.
     *
     * @return string
     */
    public function getView() : string
    {
        return $this->view;
    }
}
