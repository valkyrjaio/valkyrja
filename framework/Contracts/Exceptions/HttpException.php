<?php

namespace Valkyrja\Contracts\Exceptions;

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
     * @param string     $view       [optional] The view template name to use
     * @param int        $code       [optional] The Exception code
     */
    public function __construct(
        $statusCode,
        $message = null,
        \Exception $previous = null,
        array $headers = [],
        $view = null,
        $code = 0
    );

    /**
     * Get the status code for this exception.
     *
     * @return int
     */
    public function getStatusCode();

    /**
     * Get the headers set for this exception.
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Get the headers set for this exception.
     *
     * @return array
     */
    public function getView();
}
